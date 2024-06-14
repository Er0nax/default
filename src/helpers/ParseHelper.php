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
    public function escapeString(string $string, string $regex = null): string
    {
        // Ersetzen der speziellen Wörter
        foreach ($this->getDisabledWords() as $char => $replacement) {
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
    private function getDisabledWords(): array
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
}