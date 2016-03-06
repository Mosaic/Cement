<?php

namespace Mosaic\Cement\Tests\Bootstrappers;

use Mosaic\Cement\Mosaic;
use Mosaic\Container\Container;
use Mosaic\Cement\Bootstrap\RegisterDefinitions;
use Mosaic\Cement\Components\Registry;

class RegisterDefinitionsTest extends \PHPUnit_Framework_TestCase
{
    public $bootstrapper;

    public $app;

    public $container;

    public function setUp()
    {
        $this->app       = \Mockery::mock(Mosaic::class);
        $this->container = \Mockery::mock(Container::class);

        $this->bootstrapper = new RegisterDefinitions($this->container);
    }

    public function test_it_registers_definitions()
    {
        $this->app->shouldReceive('getRegistry')->once()->andReturn($registry = \Mockery::mock(Registry::class));
        $registry->shouldReceive('getDefinitions')->once()->andReturn([
            'abstract' => 'concrete'
        ]);

        $this->container->shouldReceive('bind')->once()->with('abstract', 'concrete');

        $this->bootstrapper->bootstrap($this->app);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
