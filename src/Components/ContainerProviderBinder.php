<?php

namespace Mosaic\Cement\Components;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Container\Container;

class ContainerProviderBinder implements ProviderBinder
{
    /**
     * @var Container
     */
    private $container;

    /**
     * ContainerDefinitionBinder constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param DefinitionProviderInterface $provider
     */
    public function bind(DefinitionProviderInterface $provider)
    {
        foreach ($provider->getDefinitions() as $abstract => $concrete) {
            $this->define($abstract, $concrete);
        }
    }

    /**
     * @param $abstract
     * @param $concrete
     */
    private function define($abstract, $concrete)
    {
        if (is_string($concrete) || is_callable($concrete)) {
            $this->container->bind($abstract, $concrete);
        } else {
            $this->container->instance($abstract, $concrete);
        }
    }
}
