<?php

namespace Mosaic\Tests;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mockery\MockInterface;
use Mosaic\Cement\Application;
use Mosaic\Cement\Components\Registry;
use Mosaic\Common\Components\AbstractComponent;
use Mosaic\Common\Conventions\DefaultFolderStructure;
use Mosaic\Common\Conventions\FolderStructureConvention;
use Mosaic\Container\Container;
use Mosaic\Container\ContainerDefinition;
use Mosaic\Http\Request;
use PHPUnit_Framework_TestCase;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function test_it_inits_a_registry_when_application_is_first_created()
    {
        $app = new Application(__DIR__);

        $this->assertInstanceOf(Registry::class, $app->getRegistry());
    }

    public function test_it_defines_a_default_container_when_application_is_first_created()
    {
        $app = new Application(__DIR__);

        $this->assertInstanceOf(Container::class, $app->getContainer());
    }

    public function test_can_set_a_custom_container_definition()
    {
        $app = new Application(__DIR__, ContainerDefinitionStub::class);

        $this->assertInstanceOf(Container::class, $app->getContainer());
        $this->assertEquals(ContainerDefinitionStub::$mockInstance, $app->getContainer());
    }

    public function test_can_define_a_custom_container_definition()
    {
        $app = new Application(__DIR__);
        $app->defineContainer(new ContainerDefinitionStub);

        $this->assertInstanceOf(Container::class, $app->getContainer());
        $this->assertEquals(ContainerDefinitionStub::$mockInstance, $app->getContainer());
    }

    public function test_app_can_capture_a_request()
    {
        $requestMock = \Mockery::mock(Request::class);

        $app = new Application(__DIR__);
        $app->getContainer()->bind(Request::class, function () use ($requestMock) {
            return $requestMock;
        });

        $this->assertEquals($requestMock, $app->captureRequest());
    }

    public function test_can_add_a_components()
    {
        $app = new Application(__DIR__);

        $this->assertFalse($app->getContainer()->has('abstract'));

        $app->components(
            SomeComponentStub::stub()
        );

        $this->assertTrue($app->getContainer()->has('abstract'));
    }

    public function test_can_add_a_component()
    {
        $app = new Application(__DIR__);

        $this->assertFalse($app->getContainer()->has('abstract'));

        $app->component(
            SomeComponentStub::stub()
        );

        $this->assertTrue($app->getContainer()->has('abstract'));
    }

    public function test_can_add_a_provider()
    {
        $app = new Application(__DIR__);

        $this->assertFalse($app->getContainer()->has('abstract'));

        $app->provide(
            new SomeProviderStub()
        );

        $this->assertTrue($app->getContainer()->has('abstract'));
    }

    public function test_can_get_default_folder_structure()
    {
        $app             = new Application(__DIR__);
        $folderStructure = $app->getFolderStructure();

        $this->assertInstanceOf(FolderStructureConvention::class, $folderStructure);
        $this->assertInstanceOf(DefaultFolderStructure::class, $folderStructure);
    }

    public function test_can_set_custom_folder_structure()
    {
        $app = new Application(__DIR__);
        $app->setFolderStructure(new CustomFolderStructure);
        $folderStructure = $app->getFolderStructure();

        $this->assertInstanceOf(FolderStructureConvention::class, $folderStructure);
        $this->assertInstanceOf(CustomFolderStructure::class, $folderStructure);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}

class ContainerDefinitionStub implements ContainerDefinition
{
    /**
     * @var MockInterface
     */
    public static $mockInstance;

    /**
     * @return Container
     */
    public function getContainerImplementation() : Container
    {
        self::$mockInstance = \Mockery::mock(Container::class);
        self::$mockInstance->shouldReceive('instance');

        return self::$mockInstance;
    }
}

class SomeComponentStub extends AbstractComponent
{
    public function resolveStub()
    {
        return [
            new SomeProviderStub
        ];
    }

    /**
     * @param  callable $callback
     * @return array
     */
    public function resolveCustom(callable $callback) : array
    {
        return $callback();
    }
}

class SomeProviderStub implements DefinitionProviderInterface
{
    public function getDefinitions()
    {
        return [
            'abstract' => 'concrete'
        ];
    }
}

class CustomFolderStructure implements FolderStructureConvention
{
    /**
     * @return string
     */
    public function basePath() : string
    {
    }

    /**
     * @return array
     */
    public function viewPaths() : array
    {
    }

    /**
     * @return string
     */
    public function viewCachePath() : string
    {
    }

    /**
     * @return string
     */
    public function cachePath() : string
    {
    }

    /**
     * @return string
     */
    public function storagePath() : string
    {
    }
}
