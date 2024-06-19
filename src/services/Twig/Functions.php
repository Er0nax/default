<?php

namespace src\services\Twig;

use src\helpers\PageHelper;
use src\modules\web\assets\SiteAssetBundle;

/**
 * Functions that can be used inside a twig file.
 */
class Functions
{
    public array $entry = [];

    /**
     * Returns an env variable or false if it does not exist.
     * @param string $key
     * @return string|array|bool
     */
    public function getEnv(string $key): string|array|bool
    {
        return getenv($key);
    }

    /**
     * Returns the current version for a type
     * @param string $type
     * @return string
     */
    public function getVersion(string $type = 'css'): string
    {
        return 'v.1.0.0';
    }

    /**
     * Returns the site asset bundle data as json
     * @return false|string
     */
    public function getSiteBundleVariables(): bool|string
    {
        return json_encode(SiteAssetBundle::getAll($this->entry));
    }

    /**
     * Dump and dies a value.
     * @param mixed $data
     * @param bool $die
     * @return void
     */
    public function dump(mixed $data, bool $die = false): void
    {
        echo '<pre style="background-color: #fff; border: 3px solid #ddd; margin: 10px; padding: 5px;">';
        var_dump($data);
        echo '</pre>';

        if ($die) {
            exit();
        }
    }

    /**
     * Returns all pages by type.
     * @param string $type
     * @return array|bool|string
     */
    public function getPages(string $type = 'all'): bool|array|string
    {
        return PageHelper::getPages($type);
    }
}