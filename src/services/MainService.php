<?php

namespace src\services;

class MainService
{
    /**
     * Echos given data as json.
     * @param mixed $data
     * @param bool $error
     * @return void
     */
    public function render(mixed $data, bool $error = true): void
    {
        // set header
        $this->setHeader();

        // set content
        $content = [
            'error' => $error,
            'response' => $data
        ];

        // echo as json format
        echo json_encode($content);
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