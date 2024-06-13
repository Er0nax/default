<?php

namespace modules;

use modules\components\Database;
use modules\components\Env;
use modules\components\Router;

// ini_set('display_errors', 'off');

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