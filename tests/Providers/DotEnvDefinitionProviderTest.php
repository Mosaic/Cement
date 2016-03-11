<?php

namespace Mosaic\Cement\Tests\Providers;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Cement\EnvironmentVariables\EnvironmentVariablesLoader;
use Mosaic\Cement\Providers\DotEnvProvider;

class DotEnvDefinitionProviderTest extends \PHPUnit_Framework_TestCase
{
    public function getProvider() : DefinitionProviderInterface
    {
        return new DotEnvProvider();
    }

    public function shouldDefine() : array
    {
        return [
            EnvironmentVariablesLoader::class
        ];
    }

    public function test_defines_all_required_contracts()
    {
        $definitions = $this->getProvider()->getDefinitions();
        foreach ($this->shouldDefine() as $define) {
            $this->assertArrayHasKey($define, $definitions);
        }
    }
}
