<?php

namespace src\services;

use src\Config;
use src\controllers\Entry;
use src\helpers\CacheHelper;

/**
 * Translation Service
 */
class Translation
{
    private string $lang;
    public array $translations = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // get lang
        $this->lang = Config::getConfig('lang', getenv('LANG'));

        // get all translations from db
        $this->translations = $this->getTranslationsFromDBOrSession();
    }

    /**
     * @param string $string
     * @param array|string $variables
     * @param string $category
     * @return string
     */
    public function getTranslation(string $string, array|string $variables = [], string $category = 'site'): string
    {
        // If the second parameter is a string, treat it as the category name
        if (is_string($variables)) {
            $category = $variables;
            $variables = [];
        }

        // get the value for the db
        $value = strtolower($string);

        // check if translations are given in object
        if (!empty($this->translations[$category][$value])) {

            // check if lang is given
            if (!empty($this->translations[$category][$value][$this->lang])) {

                // return the translation from the array
                return $this->insertVariables($this->translations[$category][$value][$this->lang], $variables);
            }
        }

        // add it to db/object
        $this->addTranslation($value, $string, $category);

        // return the original content
        return self::insertVariables($string, $variables);
    }

    /**
     * @param string $value
     * @param string $string
     * @param string $category
     * @return void
     */
    private function addTranslation(string $value, string $string, string $category = 'site'): void
    {
        // insert if it does not exist
        $entry = new Entry();
        $entry->insert('translations', ['value' => $value, 'category' => $category, 'en' => $string]);

        // add to object
        $this->translations[$category][$value] = [
            'category' => $category,
            'value' => $value,
            'de' => '',
            'en' => $string,
            'active' => true
        ];
    }

    /**
     * @return array
     */
    private function getTranslationsFromDBOrSession(): array
    {
        // exist in session? can be null
        $translationsFromCache = CacheHelper::getCache('translations', 'translations');

        // not null?
        if (!empty($translationsFromCache)) {

            // return the cached translations
            return $translationsFromCache;
        }

        // get all from db
        $entry = new Entry();
        $entry->columns(['translations' => ['*']])->tables('translations')->where(['translations' => [['active', true]]]);
        $translationsFromDB = $entry->all();

        // empty array
        $translations = [];

        // loop through db translations
        foreach ($translationsFromDB as $translation) {

            // add by category > value
            $translations[$translation['category']][$translation['value']] = $translation;
        }

        // save in cache
        CacheHelper::setCache($translations, 'translations', 1800);

        // return the translations from db
        return $translations;
    }

    /**
     * @param string $string
     * @param array $variables
     * @return string
     */
    private function insertVariables(string $string, array $variables = []): string
    {
        // Perform the replacements
        foreach ($variables as $key => $value) {
            $string = str_replace('{' . $key . '}', $value, $string);
        }

        return $string;
    }
}