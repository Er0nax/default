<?php

namespace src\services\Twig;

use src\services\Translation;

/**
 * Custom Twig filters
 */
class Filters
{

    /**
     * returns the translated value.
     * @param string $text
     * @param array $variables
     * @return string
     */
    public function t(string $string, array|string $variables = [], string $category = 'site'): string
    {
        return Translation->getTranslation($string, $variables, $category);
    }
}