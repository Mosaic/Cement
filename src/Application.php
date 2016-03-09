<?php

namespace Mosaic\Cement;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Cement\Components\ContainerProviderBinder;
use Mosaic\Cement\Components\Registry;
use Mosaic\Common\Components\Component;
use Mosaic\Container\Container;
use Mosaic\Container\ContainerDefinition;
use Mosaic\Container\Definitions\LaravelContainerDefinition;
use Mosaic\Http\Request;

class Application
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param string $containerDefinition
     */
    public function __construct(string $containerDefinition = LaravelContainerDefinition::class)
    {
        $this->defineContainer(new $containerDefinition);

        $this->registry = new Registry(
            new ContainerProviderBinder($this->container)
        );
    }

    /**
     * @return Request
     */
    public function captureRequest() : Request
    {
        return $this->getContainer()->make(Request::class);
    }

    /**
     * @param Component[] $components
     */
    public function components(Component  ...$components)
    {
        foreach ($components as $component) {
            $this->component($component);
        }
    }

    /**
     * @param Component $component
     */
    public function component(Component $component)
    {
        $this->registry->add($component);
    }

    /**
     * @param DefinitionProviderInterface $provider
     */
    public function provide(DefinitionProviderInterface $provider)
    {
        $this->registry->provide($provider);
    }

    /**
     * @return Container
     */
    public function getContainer() : Container
    {
        return $this->container;
    }
    
    /**
     * Define a container implementation
     *
     * @param ContainerDefinition $definition
     *
     * @return Container
     */
    public function defineContainer(ContainerDefinition $definition) : Container
    {
        $this->container = $definition->getContainerImplementation();
        $this->container->instance(Container::class, $this->container);

        return $this->container;
    }
}
