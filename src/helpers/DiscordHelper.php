<?php

namespace src\helpers;

class DiscordHelper
{
    /**
     * builds an array of given content.
     * @param array $information [title, description, url, color, fields[name, value]]
     * @return array
     */
    public static function buildEmbed(array $information): array
    {
        // set default information
        $content = [
            'title' => $information['title'] ?? null,
            'description' => $information['description'] ?? null,
            'url' => $information['url'] ?? null,
            'color' => hexdec($information['color'] ?? '#ffffff'),
            'footer' => [
                'text' => $information['footer_text'] ?? null
            ],
            'thumbnail' => [
                'url' => $information['thumbnail_url'] ?? null
            ]
        ];

        // add fields
        foreach ($information['fields'] as $field) {

            // check if name and value not empty
            if (!empty($field['name']) && !empty($field['value'])) {

                // add to content
                $content['fields'][] = [
                    'name' => $field['name'],
                    'value' => $field['value']
                ];
            }
        }

        // convert timestamp
        $content['timestamp'] = date("c", strtotime($information['timestamp'] ?? 'now'));

        // return the content
        return $content;
    }
}