<?php

namespace Mosaic\Cement\Tests\Components;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mockery\Mock;
use Mosaic\Cement\Components\ContainerProviderBinder;
use Mosaic\Container\Container;

class ContainerProviderBinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerProviderBinder
     */
    protected $binder;

    /**
     * @var Mock
     */
    protected $container;

    public function setUp()
    {
        $this->binder = new ContainerProviderBinder(
            $this->container = \Mockery::mock(Container::class)
        );
    }

    public function test_can_define_instances()
    {
        $object = new \stdClass();

        $this->container->shouldReceive('instance')->with('abstract', $object)->once();

        $this->binder->bind(new InstanceProvider($object));
    }

    public function test_can_define_string_class_bindings()
    {
        $this->container->shouldReceive('bind')->with('abstract', 'concrete')->once();

        $this->binder->bind(new StringClassProvider());
    }

    public function test_can_define_closure_bindings()
    {
        $this->container->shouldReceive('bind')->once();

        $this->binder->bind(new ClosureBindingProvider());
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}

class InstanceProvider implements DefinitionProviderInterface
{
    /**
     * @var \stdClass
     */
    private $object;

    public function __construct(\stdClass $object)
    {
        $this->object = $object;
    }

    public function getDefinitions()
    {
        return [
            'abstract' => $this->object
        ];
    }
}

class StringClassProvider implements DefinitionProviderInterface
{
    public function getDefinitions()
    {
        return [
            'abstract' => 'concrete'
        ];
    }
}

class ClosureBindingProvider implements DefinitionProviderInterface
{
    public function getDefinitions()
    {
        return [
            'abstract' => function () {
                return 'concrete';
            }
        ];
    }
}
