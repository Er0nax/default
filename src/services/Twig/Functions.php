<?php

namespace src\services\Twig;

use src\modules\web\assets\SiteAssetBundle;

/**
 * Functions that can be used inside a twig file.
 */
class Functions
{
    public function getEnv(string $key)
    {
        return getenv($key);
    }

    public function getVersion(string $type = 'css')
    {
        return $type;
    }

    public function getSiteBundleVariables()
    {
        return json_encode(SiteAssetBundle::getAll());
    }
}