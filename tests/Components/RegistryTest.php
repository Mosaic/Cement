<?php

namespace Mosaic\Cement\Tests\Components;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mockery\Mock;
use Mosaic\Cement\Components\ProviderBinder;
use Mosaic\Cement\Components\Registry;
use Mosaic\Common\Components\Component;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Mock
     */
    protected $binder;

    public function setUp()
    {
        $this->registry = new Registry(
            $this->binder = \Mockery::mock(ProviderBinder::class)
        );
    }

    public function test_can_add_a_component()
    {
        $provider = new SomeProvider();

        $this->binder->shouldReceive('bind')->with($provider)->once();

        $this->registry->add(new SomeComponent($provider));
    }

    public function test_can_add_a_provider()
    {
        $provider = new SomeProvider();

        $this->binder->shouldReceive('bind')->with($provider)->once();

        $this->registry->provide($provider);
    }
}

class SomeComponent implements Component
{
    /**
     * @var
     */
    private $provider;

    /**
     * SomeComponent constructor.
     * @param $provider
     */
    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return DefinitionProviderInterface[]
     */
    public function getProviders() : array
    {
        return [
            $this->provider
        ];
    }

    /**
     * @param string   $name
     * @param callable $callback
     */
    public static function extend(string $name, callable $callback)
    {
        // TODO: Implement extend() method.
    }
}

class SomeProvider implements DefinitionProviderInterface
{
    public function getDefinitions()
    {
        return [
            'abstract' => 'concrete'
        ];
    }
}
