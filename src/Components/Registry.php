<?php

namespace Mosaic\Cement\Components;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Common\Components\Component;

class Registry
{
    /**
     * @var ProviderBinder
     */
    protected $binder;

    /**
     * @param ProviderBinder $binder
     */
    public function __construct(ProviderBinder $binder)
    {
        $this->binder = $binder;
    }

    /**
     * @param Component $component
     */
    public function add(Component $component)
    {
        foreach ($component->getProviders() as $definition) {
            $this->provide($definition);
        }
    }

    /**
     * @param DefinitionProviderInterface $provider
     */
    public function provide(DefinitionProviderInterface $provider)
    {
        $this->binder->bind($provider);
    }
}
