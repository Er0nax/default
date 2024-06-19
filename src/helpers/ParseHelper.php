<?php

namespace src\helpers;

/**
 * Parse Helper
 */
class ParseHelper
{
    /**
     * Escapes a string and returns it.
     * @param string $string
     * @param string|null $regex
     * @return string
     */
    public static function escapeString(string $string, string $regex = null): string
    {
        // Ersetzen der speziellen Wörter
        foreach (self::getDisabledWords() as $char => $replacement) {
            $string = str_ireplace($char, $replacement, $string);
        }

        // Überprüfen und Anwenden des Regex-Musters
        if ($regex === null || @preg_match($regex, '') === false) {
            $regex = '/^[a-zA-Z0-9 .,\\-_]*$/';
        }

        // Zurückgeben der bereinigten Zeichenkette
        return preg_replace($regex, '', $string);
    }

    /**
     * @return string[] list of disallowed words
     */
    private static function getDisabledWords(): array
    {
        return [
            '<script>' => '',
            '</script>' => '',
            'SELECT' => '',
            'FROM' => '',
            'ORDER' => '',
            'WHERE' => '',
            'LIMIT' => '',
            'OFFSET' => '',
        ];
    }

    /**
     * Returns boolean whether a given value is json or not.
     * @param mixed $data
     * @return bool
     */
    public static function isJson(mixed $data): bool
    {
        // Decodes the JSON string, while suppressing errors
        json_decode($data);
        // Returns true if the JSON string was decoded correctly
        return (json_last_error() == JSON_ERROR_NONE);
    }
}