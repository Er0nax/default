<?php

namespace controllers;

class Translation extends Controller
{
    private string $language = 'de';
    private array $translations = [];

    public function __construct()
    {
        parent::__construct();

        // get translations from db
        $this->translations = $this->getTranslationsFromDbOrSession();
    }

    /**
     * @param string $value
     * @param string $category
     * @return string
     */
    public function getTranslation(string $value, string $category = 'site'): string
    {
        // is value in translations array?
        if (empty($this->translations[$value])) {
            // does it exist in db?
            if (!$this->checkIfTranslationExistsInDB($value)) {
                // insert it into db
                if ($this->insertTranslationInDB($value, $category)) {
                    // added to db
                }

                // return default value as no value is set yet
                return $value;
            }
        }

        // does language exist?
        if (empty($this->translations[$value][$this->language])) {
            // return default value
            return $value;
        }

        return $this->translations[$value][$this->language];
    }

    /**
     * @return array
     */
    private function getTranslationsFromDbOrSession(): array
    {
        // default empty array
        $translations = [];

        // does session exists?
        if (!empty($_SESSION['translations'])) {
            // set translations from session
            return $_SESSION['translations'];
        }

        // set translations from db
        $entry = new Entry();
        $translationsFromDB = $entry->columns(['translations' => ['value', 'category', 'de', 'en']])
            ->tables('translations')
            ->where(['translations' => [['active', 'true']]])
            ->all();

        // translations not empty?
        if (!empty($translationsFromDB)) {

            // loop through them
            foreach ($translationsFromDB as $translation) {
                // add specific values
                $translations[$translation['value']] = [
                    'de' => $translation['de'],
                    'en' => $translation['en'],
                ];
            }

            // add to session
            $_SESSION['translations'] = $translations;
        }

        // return them
        return $translations;
    }

    /**
     * @param string $value
     * @param string $category
     * @return bool
     */
    private function insertTranslationInDB(string $value, string $category): bool
    {
        // try to insert into db
        $entry = new Entry();
        $success = $entry->insert('translations', ['value' => $value, 'category' => $category]);

        // is inserted?
        if ($success) {
            // add to session
            $_SESSION['translations'][$value] = [
                'de' => '',
                'en' => '',
            ];
        }

        return $success;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function checkIfTranslationExistsInDB(string $value): bool
    {
        $entry = new Entry();
        return $entry->columns(['translations' => ['id']])
            ->tables('translations')
            ->where(['translations' => [['value', $value]]])
            ->exists();
    }
}