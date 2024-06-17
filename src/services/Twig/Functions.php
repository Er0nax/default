<?php

namespace src\services\Twig;

use src\modules\web\assets\SiteAssetBundle;

/**
 * Functions that can be used inside a twig file.
 */
class Functions
{
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
        return json_encode(SiteAssetBundle::getAll());
    }
}