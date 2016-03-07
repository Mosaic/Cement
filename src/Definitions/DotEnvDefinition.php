<?php

namespace Mosaic\Definitions;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Cement\EnvironmentVariables\Adapters\DotEnvVariableLoader;
use Mosaic\Contracts\EnvironmentVariablesLoader;

class DotEnvDefinition implements DefinitionProviderInterface
{
    /**
     * Returns the definition to register in the container.
     *
     * Definitions must be indexed by their entry ID. For example:
     *
     *     return [
     *         'logger' => ...
     *         'mailer' => ...
     *     ];
     *
     * @return array
     */
    public function getDefinitions()
    {
        return [
            EnvironmentVariablesLoader::class => function () {
                return new DotEnvVariableLoader();
            }
        ];
    }
}
