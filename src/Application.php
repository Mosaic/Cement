<?php

namespace Mosaic\Cement;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Cement\Components\ContainerProviderBinder;
use Mosaic\Cement\Components\Registry;
use Mosaic\Common\Components\Component;
use Mosaic\Common\Conventions\DefaultFolderStructure;
use Mosaic\Common\Conventions\FolderStructureConvention;
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
     * @var FolderStructureConvention
     */
    protected $folderStructure;

    /**
     * @param string $path
     * @param string $containerDefinition
     */
    public function __construct(string $path, string $containerDefinition = LaravelContainerDefinition::class)
    {
        $this->defineContainer(new $containerDefinition);

        $this->registry = new Registry(
            new ContainerProviderBinder($this->container)
        );

        $this->folderStructure = new DefaultFolderStructure(
            $path
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

    /**
     * @return DefaultFolderStructure|FolderStructureConvention
     */
    public function getFolderStructure()
    {
        return $this->folderStructure;
    }

    /**
     * @param FolderStructureConvention $folderStructure
     */
    public function setFolderStructure(FolderStructureConvention $folderStructure)
    {
        $this->folderStructure = $folderStructure;
    }
}
