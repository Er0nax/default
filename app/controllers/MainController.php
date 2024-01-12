<?php

namespace controllers;

/**
 * Main Controller with basic functions
 */
class Controller
{
    public bool $isLoggedIn = false;

    /**
     * controller constructor
     */
    public function __construct()
    {
        $this->setLoggedIn();
    }

    /**
     * redirect to a specific page
     * @param $page
     * @return void
     */
    public function redirect($page): void
    {
        header('Location: ' . $page);
        exit;
    }

    /**
     * sets the loggedin boolean
     * @return void
     */
    public function setLoggedIn(): void
    {
        $this->isLoggedIn = !empty($_SESSION['user']['id']);
    }

    /**
     * returns the content in json format
     * @param $content
     * @param string $status
     * @return void
     */
    protected function render($content, string $status = 'error'): void
    {
        if (is_string($content)) {
            die(json_encode(['content' => $content, 'status' => $status]));
        }

        if (is_array($content)) {
            die(json_encode(['content' => $content, 'status' => $status]));
        }
    }

    /**
     * @param $content
     * @param $type
     * @param $file
     * @param $line
     * @return void
     */
    protected function log($content, $type = 'error', $file = 'unknown', $line = 'unknown'): void
    {
        // do sometihing
    }
}