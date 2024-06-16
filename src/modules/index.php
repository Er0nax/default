<?php

namespace src\modules;

use src\helpers\DiscordHelper;
use src\services\DiscordService;

/**
 * Module for the index page.
 */
class index extends main
{
    public function webhookExample()
    {
        $embed = DiscordHelper::buildEmbed([
            'title' => 'Simsontreffen',
            'description' => 'Treffen Beschreibung',
            'url' => 'https://eronax.de/meeting/treffen-helmsdorf',
            'color' => '#123456',
            'footer_text' => 'In Sachsen',
            'thumbnail_url' => 'https://eronax.de/assets/images/covers/Eronax_Treffen_Helmsdorf_2024-09-07_02_09_00_cover.jpg',
            "fields" => [
                [
                    "name" => "Datum",
                    "value" => "07.09.24"
                ],
                [
                    "name" => "Ort",
                    "value" => "Helmsdorf"
                ],
                [
                    "name" => "Adresse",
                    "value" => "Wesenitzer Straße, 48"
                ],
                [
                    "name" => "Postleitzahl",
                    "value" => "01833"
                ],
                [
                    "name" => "Eintritt",
                    "value" => "Frei"
                ],
                [
                    "name" => "Essen vor Ort",
                    "value" => "Ja"
                ],
                [
                    "name" => "Toiletten vor Ort",
                    "value" => "Ja"
                ]
            ]
        ]);

        $DiscordService = new DiscordService();
        $DiscordService->setUrl('https://discord.com/api/webhooks/1251857608437137408/K35swJDkLKClWfmJhfJeYlg4m71Vx1uTdrv3fIauXNpvXKkxdWLV-Y95DNzk9hUFpZ3G')
            ->setUsername('Simsonmeets')
            ->addEmbed($embed)
            ->send();
    }
}