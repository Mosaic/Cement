<?php

namespace Mosaic\Cement\Bootstrap;

use Mosaic\Container\Container;
use Mosaic\Contracts\Application;

class RegisterDefinitions
{
    /**
     * @var Container
     */
    private $container;

    /**
     * RegisterDefinitions constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        foreach ($app->getRegistry()->getDefinitions() as $abstract => $concrete) {
            if (is_string($concrete) || is_callable($concrete)) {
                $this->container->bind($abstract, $concrete);
            } else {
                $this->container->instance($abstract, $concrete);
            }
        }
    }
}
