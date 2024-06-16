<?php

namespace src\services;

/**
 * Discord Service
 */
class DiscordService extends MainService
{
    private ?string $url = null;
    private ?string $content = null;
    private ?string $username = null;
    private bool $tts = false;
    private array $embeds = [];
    private array $footer = [];
    private string $avatar = 'https://eronax.de/assets/manifest/icons/icon-48x48.png?v=2.0.2';

    /**
     * sets the url for the webhook
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * sets the content (title) for the webhook
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * sets the username for the webhook
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * sets the tts (boolean) for the webhook
     * @param bool $tts
     * @return $this
     */
    public function setTTS(bool $tts): static
    {
        $this->tts = $tts;

        return $this;
    }

    /**
     * sets the avatar for the webhook.
     * @param string $avatar
     * @return $this
     */
    public function setAvatar(string $avatar): static
    {
        // valid link?
        if (!filter_var($avatar, FILTER_VALIDATE_URL)) {
            $this->render('Empty or invalid avatar specified. You can set an valid url with "setAvatar()"');
        }

        return $this;
    }

    /**
     * add one embed for the webhook
     * @param array $embed
     * @return $this
     */
    public function addEmbed(array $embed): static
    {
        $this->embeds[] = $embed;

        return $this;
    }

    /**
     * send the discord webhook
     * @return void
     */
    public function send(): void
    {
        // check all values or render error as json
        $this->checkValues();

        // build the content as json format
        $contentAsJson = $this->getValuesAsJson();

        // if error, $contentAsJson will be false
        if (!empty($contentAsJson)) {

            // send http request to given webhook url
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $contentAsJson);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            // no response means good
            if (empty($response)) {
                $this->render([
                    'msg' => 'Webhook has been sent.',
                    'webhook' => json_decode($contentAsJson)
                ], false);
            }
        }

        $this->render('There was an error while sending the webhook.');
    }

    /**
     * checks all important values and returns error in json format.
     * @return void
     */
    private function checkValues(): void
    {
        // valid link?
        if (empty($this->url) || !filter_var($this->url, FILTER_VALIDATE_URL)) {
            $this->render('Empty or invalid url specified. You can set an valid url with "setUrl()"');
        }

        // avatar link?
        if (empty($this->avatar) || !filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            $this->render('Empty or invalid avatar specified. You can set an valid url with "setAvatar()"');
        }

        // content given?
        if (empty($this->username)) {
            $this->render('Empty or invalid username specified. You can set an valid url with "setUsername()"');
        }

        // content given?
        if (empty($this->embeds)) {
            $this->render('Empty or invalid embeds specified. You can set an valid url with "setEmbed()"');
        }
    }

    /**
     * builds the json formatted webhook data.
     * @return false|string
     */
    private function getValuesAsJson(): false|string
    {
        return json_encode([
            'content' => $this->content,
            'username' => $this->username,
            'tts' => $this->tts,
            'embeds' => $this->embeds,
            'avatar_url' => $this->avatar
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}