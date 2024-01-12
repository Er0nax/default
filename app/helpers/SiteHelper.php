<?php

namespace helpers;

/**
 * site helper
 */
class SiteHelper
{
    /**
     * @param $content
     * @param bool $die
     * @return void
     */
    public function _dump($content, bool $die = true): void
    {
        echo '<pre style="color: white; background: rgba(0, 0, 0, 0.5); padding: 10px;">';
        var_dump($content);
        echo '</pre>';

        if ($die) {
            die();
        }
    }

    /**
     * @param string|null $src
     * @param string $folder
     * @return string|null
     */
    public function getImage(string $src = null, string $folder = 'general'): ?string
    {
        // set default path
        $defaultImagePath = match ($folder) {
            'icons' => 'assets/images/banners/',
            default => 'assets/images/general',
        };

        // src empty? return default
        if (empty($src)) {
            return $defaultImagePath . 'default.jpg';
        }

        // contains http/https? return whole src -> else add default path
        if (!str_contains($src, 'http')) {
            $srcTemp = $defaultImagePath . $src;
            if (file_exists($srcTemp)) {
                $src = $srcTemp;
            } else {
                $src = $defaultImagePath . 'default.png';
            }
        } else {
            // it's online so check if 404
            /*
            $imgHeaders = @get_headers( str_replace(' ', '%20', $src) )[0];

            if( $imgHeaders == 'HTTP/1.1 404 Not Found' ) {
                return $defaultImagePath . 'default.png';
            }
            */
        }

        return $src;
    }
}