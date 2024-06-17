<?php

namespace src\modules\web\assets;

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
            'baseUrl' => getenv('BASE_URL'),
        ];
    }
}