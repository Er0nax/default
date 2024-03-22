<?php

namespace modules\site;

use Main;

/**
 * index site module for index page
 */
class error extends Main
{
    public function __construct()
    {
        parent::__construct();
        global $app;
        $app->page->setPage(error::class);
        var_dump($app->page->info);
    }
}