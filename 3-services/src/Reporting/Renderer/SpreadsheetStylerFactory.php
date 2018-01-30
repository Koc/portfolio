<?php

namespace App\Reporting\Renderer;

use Psr\Container\ContainerInterface;

class SpreadsheetStylerFactory
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getStyler(string $stylerClass): SpreadsheetStyler
    {
        return $this->container->get($stylerClass);
    }
}
