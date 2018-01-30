<?php

namespace App\DependencyInjection\Compiler;

use App\Reporting\ReportingFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterReportingServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $services = [];

        foreach ($container->findTaggedServiceIds('app.reporting.generator', true) as $serviceId => $tags) {
            $services[$serviceId] = new Reference($serviceId);
        }

        foreach ($container->findTaggedServiceIds('app.reporting.source_provider', true) as $serviceId => $tags) {
            $services[$serviceId] = new Reference($serviceId);
        }

        $container
            ->getDefinition(ReportingFactory::class)
            ->addArgument(ServiceLocatorTagPass::register($container, $services));
    }
}
