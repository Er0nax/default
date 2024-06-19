<?php

namespace src\api\controllers;

use src\Config;
use src\helpers\CacheHelper;

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
    public function render(mixed $data, string|int|bool $status = 200, $config = []): void
    {
        $this->setHeader();
        $config = array_merge(Config::getConfig('api', []), $config);
        $error = !($status == 200 || $status == true || $status == 'success' || $status == 'OK');

        // check if data is string
        if (is_string($data) && $config['translate']) {
            // translate string
            $data = $this->t($data);
        }

        // check if is object?
        if (is_object($data)) {
            // convert to array
            $data = (array)$data;
        }

        // should be cached?
        if ($config['cache']) {
            // save in cache
            $hash = CacheHelper::generateHash($this->params);
            CacheHelper::setCache($data, $hash, $config['duration']);
        }

        // return as json encode
        echo json_encode([
            'error' => $error,
            'cached' => $config['cached'] ?? false,
            'response' => $data,
        ]);

        exit();
    }

    /**
     * Returns the translated value.
     * @param string $value
     * @return string
     */
    private function t(string $value): string
    {
        // todo add translation
        return $value;
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