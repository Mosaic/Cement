<?php

namespace Mosaic\Cement\Components;

use Interop\Container\Definition\DefinitionProviderInterface;

interface ProviderBinder
{
    /**
     * @param DefinitionProviderInterface $provider
     */
    public function bind(DefinitionProviderInterface $provider);
}
