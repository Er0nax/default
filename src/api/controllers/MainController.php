<?php

namespace src\api\controllers;

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
    public function render(mixed $data): void
    {
        $this->setHeader();


        echo json_encode($data);
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