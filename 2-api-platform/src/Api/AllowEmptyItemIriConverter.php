<?php

namespace App\Api;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Api\UrlGeneratorInterface;
use ApiPlatform\Core\Bridge\Symfony\Routing\IriConverter;
use ApiPlatform\Core\Exception\ItemNotFoundException;

class AllowEmptyItemIriConverter implements IriConverterInterface
{
    public const ENABLED_SKIP_NON_EXISTENT_FLAG = 'skip_non_existent_references';

    private $decorated;

    public function __construct(IriConverter $decorated)
    {
        $this->decorated = $decorated;
    }

    public function getItemFromIri(string $iri, array $context = [])
    {
        try {
            return $this->decorated->getItemFromIri($iri, $context);
        } catch (ItemNotFoundException $e) {
            if (empty($context[self::ENABLED_SKIP_NON_EXISTENT_FLAG])) {
                throw $e;
            }

            return null;
        }
    }

    public function getIriFromItem($item, int $referenceType = UrlGeneratorInterface::ABS_PATH): string
    {
        return $this->decorated->getIriFromItem($item, $referenceType);
    }

    public function getIriFromResourceClass(
        string $resourceClass,
        int $referenceType = UrlGeneratorInterface::ABS_PATH
    ): string {
        return $this->decorated->getIriFromResourceClass($resourceClass, $referenceType);
    }

    public function getItemIriFromResourceClass(
        string $resourceClass,
        array $identifiers,
        int $referenceType = UrlGeneratorInterface::ABS_PATH
    ): string {
        return $this->decorated->getItemIriFromResourceClass($resourceClass, $identifiers, $referenceType);
    }

    public function getSubresourceIriFromResourceClass(
        string $resourceClass,
        array $identifiers,
        int $referenceType = UrlGeneratorInterface::ABS_PATH
    ): string {
        return $this->decorated->getSubresourceIriFromResourceClass($resourceClass, $identifiers, $referenceType);
    }
}
