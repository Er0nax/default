<?php

namespace modules\components;

use modules\Config;
use modules\helpers\FileHelper;

/**
 * Env component
 */
class Env
{
    private array $envVariables = [];

    /**
     * Sets all safe .env variables to php's env
     */
    public function __construct()
    {
        // new helper
        $FileHelper = new FileHelper();

        // check if .env file exists
        if (!$FileHelper->exist('.env')) {
            exit('Could not locate .env file!');
        }

        // get variables
        $allVariables = parse_ini_file($FileHelper->get('.env'));
        $this->envVariables = $this->getSafeVariables($allVariables);

        // put all env variables inside php's env
        foreach ($this->envVariables as $name => $value) {
            putenv("{$name}={$value}");
        }

        // check for environment
        $environment = getenv('ENVIRONMENT');
        if ($environment != 'production' && $environment != 'dev') {
            exit('Missing or invalid <b>ENVIRONMENT</b> in .env file!');
        }
    }

    /**
     * Returns all safe variables.
     * @param $allVariables
     * @return mixed
     */
    private function getSafeVariables($allVariables): mixed
    {
        $unsafeVariables = Config::getUnsafeEnvVariables() ?? [];

        foreach ($unsafeVariables as $name) {
            if (isset($allVariables[$name])) {
                unset($allVariables[$name]);
            }
        }

        return $allVariables;
    }
}