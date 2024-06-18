<?php

namespace src\api\controllers;

use src\Config;

/**
 * Main API Controller
 */
class MainController
{
    protected array $params = [];

    /**
     * Echos given data as json.
     * @param mixed $data
     * @return void
     */
    public function render(mixed $data, string|int $status = 200, $config = []): void
    {
        $this->setHeader();
        $config = array_merge($config, Config::getConfig('api', []));

        echo json_encode($config);

        exit();
    }

    /**
     * sets the header as json format.
     * @return void
     */
    private function setHeader(): void
    {
        header('Content-Type: application/json');
    }
}