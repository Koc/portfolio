<?php

namespace App\Reporting;

use Psr\Container\ContainerInterface;

class RendererFactory
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getRenderer(string $format): Renderer
    {
        return $this->container->get($format);
    }
}
