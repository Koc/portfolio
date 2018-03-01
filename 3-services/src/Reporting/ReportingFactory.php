<?php

namespace App\Reporting;

use App\Reporting\Generator\Generator;
use App\Reporting\Source\ReportSourceProvider;
use Psr\Container\ContainerInterface;

class ReportingFactory
{
    private $container;

    private $generatorsMap;

    public function __construct(ContainerInterface $container, array $generatorsMap)
    {
        $this->container = $container;
        $this->generatorsMap = $generatorsMap;
    }

    public function getGenerator(string $generatorClass): Generator
    {
        if (!isset($this->generatorsMap[$generatorClass])) {
            throw new \BadMethodCallException(
                sprintf('No generator service for "%s" generator class.', $generatorClass)
            );
        }

        return $this->container->get($this->generatorsMap[$generatorClass]);
    }

    public function getSourceProvider(string $sourceProviderClass): ReportSourceProvider
    {
        return $this->container->get($sourceProviderClass);
    }
}
