<?php

namespace src\helpers;

use src\Config;
use src\controllers\Entry;

/**
 * Page Helper
 */
class PageHelper
{
    /**
     * Returns all available pages by a specific type.
     * @param string $type
     * @return array|bool|string
     */
    public static function getPages(string $type = 'all'): bool|array|string
    {
        $entry = new Entry();
        $entry->columns(['pages' => ['*']])
            ->tables(['pages'])
            ->where(['pages' => [['showAlways', true]]]);

        switch ($type) {
            case 'header':
                $entry->addWhere(['pages' => [['hideInHeader', false]]], 'OR');
                break;
            case 'footer':
                $entry->addWhere(['pages' => [['hideInFooter', false]]], 'OR');
                break;
        }

        $entry->addWhere(['pages' => [['active', true]]]);

        $pages = $entry->all();
        $isLoggedIn = Config::getConfig('isLoggedin', false);

        // go through settings
        foreach ($pages as $key => $page) {

            // must be logged in?
            if ($page['mustBeLoggedIn']) {

                if (!$isLoggedIn) {
                    unset($pages[$key]);
                }
            }
        }

        return $pages;
    }
}