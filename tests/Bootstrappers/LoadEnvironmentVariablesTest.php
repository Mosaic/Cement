<?php

namespace Mosaic\Cement\Tests\Bootstrappers;

use Mockery\Mock;
use Mosaic\Cement\Bootstrap\LoadEnvironmentVariables;
use Mosaic\Cement\EnvironmentVariables\EnvironmentVariablesLoader;
use Mosaic\Cement\Mosaic;

class LoadEnvironmentVariablesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LoadEnvironmentVariables
     */
    private $bootstrapper;

    /**
     * @var Mock
     */
    private $app;

    /**
     * @var Mock
     */
    private $loader;

    public function setUp()
    {
        $this->app = \Mockery::mock(Mosaic::class);

        $this->bootstrapper = new LoadEnvironmentVariables(
            $this->loader = \Mockery::mock(EnvironmentVariablesLoader::class)
        );
    }

    public function test_it_loads_configuration()
    {
        $this->app->shouldReceive('path')->once()->andReturn('some_path');
        $this->loader->shouldReceive('load')->with('some_path')->once();

        $this->bootstrapper->bootstrap($this->app);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
