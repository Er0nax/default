<?php

namespace src\modules\web\assets;

use src\Config;

/**
 * Site Asset Bundle
 */
class SiteAssetBundle
{
    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            'baseUrl' => getenv('BASE_URL') ?? null,
            'title' => getenv('TITLE') ?? null,
            'lang' => $_SESSION['lang'] ?? getenv('LANG') ?? 'en',
            'environment' => getenv('ENVIRONMENT') ?? 'production',
            'loggedIn' => Config::getConfig('isLoggedIn', false)
        ];
    }
}