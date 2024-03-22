<?php

/**
 * main site module
 */
class Main
{
    /**
     * constructor
     */
    public function __construct()
    {
        // remove page from get
        unset($_GET['page']);
    }
}