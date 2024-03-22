<?php

/**
 * index site module for index page
 */
class index extends Main
{
    public function __construct()
    {
        parent::__construct();
        global $app;
        $app->page->setPage(index::class);
    }
}