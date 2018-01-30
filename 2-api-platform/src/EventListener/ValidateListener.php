<?php

namespace App\EventListener;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ValidateListener implements EventSubscriberInterface
{
    private $validator;
    private $resourceMetadataFactory;
    private $container;

    public function __construct(ValidatorInterface $validator, ResourceMetadataFactoryInterface $resourceMetadataFactory, ContainerInterface $container = null)
    {
        $this->validator = $validator;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            // lower priority than DeserializeListener
            KernelEvents::REQUEST => ['onKernelRequest', 1],
        ];
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (
            $request->isMethodSafe(false)
            || $request->isMethod(Request::METHOD_DELETE)
            || !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !$attributes['receive']
        ) {
            return;
        }

        $data = $request->attributes->get('data');
        $resourceMetadata = $this->resourceMetadataFactory->create($attributes['resource_class']);

        if (isset($attributes['collection_operation_name'])) {
            $validationGroups = $resourceMetadata->getCollectionOperationAttribute($attributes['collection_operation_name'], 'validation_groups');
        } else {
            $validationGroups = $resourceMetadata->getItemOperationAttribute($attributes['item_operation_name'], 'validation_groups');
        }

        if (!$validationGroups) {
            // Fallback to the resource
            $validationGroups = $resourceMetadata->getAttributes()['validation_groups'] ?? null;
        }

        if (
            $this->container &&
            is_string($validationGroups) &&
            $this->container->has($validationGroups) &&
            ($service = $this->container->get($validationGroups)) &&
            is_callable($service)
        ) {
            $validationGroups = $service($data);
        } elseif (is_callable($validationGroups)) {
            $validationGroups = call_user_func_array($validationGroups, [$data]);
        }

        $violations = $this->validator->validate($data, null, (array) $validationGroups);
        if (0 !== count($violations)) {
            throw new ValidationException($violations);
        }
    }
}
