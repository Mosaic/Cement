<?php

namespace Mosaic\Cement;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Cement\Bootstrap\RegisterDefinitions;
use Mosaic\Cement\Components\Registry;
use Mosaic\Common\Components\Component;
use Mosaic\Container\Container;
use Mosaic\Container\ContainerDefinition;
use Mosaic\Container\Definitions\LaravelContainerDefinition;

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
     * @var string
     */
    protected $context;

    /**
     * @var string
     */
    protected $env = 'production';

    /**
     * @var array
     */
    protected $bootstrappers = [
        RegisterDefinitions::class,
        //LoadEnvironmentVariables::class,
        //LoadConfiguration::class,
        //LoadRoutes::class,
    ];

    /**
     * @param string $containerDefinition
     */
    public function __construct(string $containerDefinition = LaravelContainerDefinition::class)
    {
        $this->registry = new Registry();

        $this->defineContainer(new $containerDefinition);
    }

    /**
     * @param Component[] $components
     */
    public function components(Component  ...$components)
    {
        foreach ($components as $component) {
            $this->getRegistry()->add($component);
        }
    }

    /**
     * @param DefinitionProviderInterface $definition
     */
    public function define(DefinitionProviderInterface $definition)
    {
        $this->getRegistry()->define($definition, $this);
    }

    /**
     * @param string[] $definitions
     */
    public function definitions(array $definitions)
    {
        foreach ($definitions as $definition) {
            $this->define(
                $this->getContainer()->make($definition)
            );
        }
    }

    /**
     * @return Registry
     */
    public function getRegistry() : Registry
    {
        return $this->registry;
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
        $this->container = $definition->getDefinition();
        $this->container->instance(Container::class, $this->container);

        //$this->container->instance(ApplicationContract::class, $this);

        return $this->container;
    }

    /**
     * Bootstrap the Application
     */
    public function bootstrap()
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->getContainer()->make($bootstrapper)->bootstrap($this);
        }
    }

    /**
     * @return string
     */
    public function env() : string
    {
        return $this->env;
    }

    /**
     * @param string $env
     */
    public function setEnvironment(string $env)
    {
        $this->env = $env;
    }

    /**
     * @return bool
     */
    public function isLocal() : bool
    {
        return $this->env() === 'local';
    }

    /**
     * @param string $context
     */
    public function setContext(string $context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getContext() : string
    {
        return $this->context;
    }
}
