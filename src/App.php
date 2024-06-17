<?php

namespace src;

use src\components\Database;
use src\components\Env;
use src\components\Router;

/**
 * App
 */
class App
{
    /**
     * Start the application.
     * @return void
     */
    public function run(): void
    {
        // create new env and database
        define('ENV', new Env());
        define('DATABASE', new Database());

        $Router = new Router();
    }
}