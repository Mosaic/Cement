<?php

namespace Mosaic\Cement\Tests\Bootstrappers;

use Mosaic\Cement\Mosaic;
use Mosaic\Routing\RouteLoader;
use Mosaic\Routing\Router;
use Mosaic\Cement\Bootstrap\LoadRoutes;
use PHPUnit_Framework_TestCase;

class LoadRoutesTest extends PHPUnit_Framework_TestCase
{
    public function test_it_delegates_loading_the_routes_to_the_route_loader()
    {
        $loader = \Mockery::mock(RouteLoader::class);
        $loader->shouldReceive('loadRoutes')->with($router = \Mockery::mock(Router::class))->once();

        (new LoadRoutes($loader, $router))->bootstrap(\Mockery::mock(Mosaic::class));
    }
}
