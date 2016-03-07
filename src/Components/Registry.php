<?php

namespace Mosaic\Cement\Components;

use Interop\Container\Definition\DefinitionProviderInterface;

class Registry
{
    /**
     * @var array
     */
    protected static $definitions = [];

    /**
     * @param $component
     */
    public function add($component)
    {
        foreach ($component->getDefinitions() as $definition) {
            $this->define($definition);
        }
    }

    /**
     * @param DefinitionProviderInterface $definition
     */
    public function define(DefinitionProviderInterface $definition)
    {
        foreach ($definition->getDefinitions() as $abstract => $concrete) {
            $this->registerDefinition($abstract, $concrete);
        }
    }

    /**
     * @param string $as
     * @param object $define
     */
    private function registerDefinition($as, $define)
    {
        self::$definitions[$as] = $define;
    }

    /**
     * @return array
     */
    public function getDefinitions()
    {
        return self::$definitions;
    }

    /**
     * Flush the registry
     */
    public static function flush()
    {
        static::$definitions = [];
    }
}
