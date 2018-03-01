<?php

namespace App\DependencyInjection\Compiler;

use App\Reporting\Generator\Generator;
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
        $generatorsMap = [];

        foreach ($container->findTaggedServiceIds('app.reporting.generator', true) as $serviceId => $tags) {
            $services[$serviceId] = new Reference($serviceId);

            $class = $container->getDefinition($serviceId)->getClass();
            if (!is_a($class, Generator::class, true)) {
                throw new \InvalidArgumentException('Generator should implement Generator interface.');
            }
            /** @var Generator $class */
            $generatorsMap[$class::getShortAlias()] = $class;
        }

        foreach ($container->findTaggedServiceIds('app.reporting.source_provider', true) as $serviceId => $tags) {
            $services[$serviceId] = new Reference($serviceId);
        }

        $container
            ->getDefinition(ReportingFactory::class)
            ->addArgument(ServiceLocatorTagPass::register($container, $services))
            ->addArgument($generatorsMap);
    }
}
