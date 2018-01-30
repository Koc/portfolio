<?php

namespace App\Reporting\Renderer;

use Psr\Container\ContainerInterface;

class CssStylerFactory
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getStyler(string $stylerClass): CssStyler
    {
        return $this->container->get($stylerClass);
    }
}
