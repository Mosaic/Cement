<?php

namespace Mosaic\Cement\Tests\Bootstrappers;

use Mosaic\Cement\Mosaic;
use Mosaic\Config\Config;
use Mosaic\Cement\Bootstrap\LoadConfiguration;
use Mockery\Mock;

class LoadConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LoadConfiguration
     */
    private $bootstrapper;

    /**
     * @var Mock
     */
    private $app;

    /**
     * @var Mock
     */
    private $config;

    public function setUp()
    {
        $this->app = \Mockery::mock(Mosaic::class);

        $this->bootstrapper = new LoadConfiguration(
            $this->config = \Mockery::mock(Config::class)
        );
    }

    public function test_it_loads_configuration()
    {
        $this->app->shouldReceive('configPath')->once()->andReturn(__DIR__ . '/../../fixtures/config');
        $this->app->shouldReceive('setEnvironment')->once()->with('production');

        $this->config->shouldReceive('set')->with('stub', ['some' => 'value'])->once();
        $this->config->shouldReceive('get')->with('app.env', 'production')->once()->andReturn('production');
        $this->config->shouldReceive('get')->with('app.timezone', 'UTC')->once()->andReturn('UTC');

        $this->bootstrapper->bootstrap($this->app);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
