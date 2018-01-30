<?php

namespace App\Reporting;

use App\Reporting\Generator\Generator;
use App\Reporting\Source\ReportSourceProvider;
use Psr\Container\ContainerInterface;

class ReportingFactory
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getGenerator(string $generatorClass): Generator
    {
        return $this->container->get($generatorClass);
    }

    public function getSourceProvider(string $sourceProviderClass): ReportSourceProvider
    {
        return $this->container->get($sourceProviderClass);
    }
}
