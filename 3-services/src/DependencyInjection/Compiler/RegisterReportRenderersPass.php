<?php

namespace App\DependencyInjection\Compiler;

use App\Reporting\Renderer;
use App\Reporting\RendererFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterReportRenderersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $services = [];
        foreach ($container->findTaggedServiceIds('app.reporting.renderer', true) as $serviceId => $tags) {
            $rendererClass = $container->getDefinition($serviceId)->getClass();
            if (!is_a($rendererClass, Renderer::class, true)) {
                throw new \InvalidArgumentException('Tagged service must implement "Renderer" interface.');
            }

            $format = $rendererClass::getFormat();
            $services[$format] = new Reference($serviceId);
        }

        $container
            ->getDefinition(RendererFactory::class)
            ->addArgument(ServiceLocatorTagPass::register($container, $services));
    }
}
