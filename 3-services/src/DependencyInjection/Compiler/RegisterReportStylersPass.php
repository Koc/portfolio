<?php

namespace App\DependencyInjection\Compiler;

use App\Reporting\Renderer\CssStylerFactory;
use App\Reporting\Renderer\SpreadsheetStylerFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterReportStylersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->registerSpreadsheetStylers($container);
        $this->registerCssStylers($container);
    }

    private function registerSpreadsheetStylers(ContainerBuilder $container): void
    {
        $services = [];
        foreach ($container->findTaggedServiceIds('app.reporting.spreadsheet_styler', true) as $serviceId => $tags) {
            $services[$serviceId] = new Reference($serviceId);
        }

        $container
            ->getDefinition(SpreadsheetStylerFactory::class)
            ->addArgument(ServiceLocatorTagPass::register($container, $services));
    }

    private function registerCssStylers(ContainerBuilder $container)
    {
        $services = [];
        foreach ($container->findTaggedServiceIds('app.reporting.css_styler', true) as $serviceId => $tags) {
            $services[$serviceId] = new Reference($serviceId);
        }

        $container
            ->getDefinition(CssStylerFactory::class)
            ->addArgument(ServiceLocatorTagPass::register($container, $services));
    }
}
