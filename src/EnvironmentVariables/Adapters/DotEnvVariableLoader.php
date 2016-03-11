<?php

namespace Mosaic\Cement\EnvironmentVariables\Adapters;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Mosaic\Cement\EnvironmentVariables\EnvironmentVariablesLoader;

class DotEnvVariableLoader implements EnvironmentVariablesLoader
{
    /**
     * @param string $path
     */
    public function load(string $path)
    {
        try {
            (new Dotenv($path, $this->getFilename()))->load();
        } catch (InvalidPathException $e) {
            //
        }
    }

    /**
     * @return string
     */
    public function getFilename() : string
    {
        return '.env';
    }
}
