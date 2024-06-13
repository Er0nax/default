<?php

namespace modules\services\Twig;

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
}