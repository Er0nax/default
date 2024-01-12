<?php

global $env;

/**
 * containing default website settings
 */
class settings
{
    public string $cssVersion = '1.0.0';
    public string $jsVersion = '1.0.0';
    public string $manifestVersion = '1.0.0';
    public ?string $url = null;
    private env $env;

    /**
     * constructor
     */
    public function __construct()
    {
        global $env;

        if (!empty($env)) {
            $this->env = $env;
        }

        // set url
        $this->setUrl();
    }

    /**
     * @return void
     */
    private function setUrl(): void
    {
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $this->url = $this->env->variables['LOCALHOSTURL'];
        } else {
            $this->url = $this->env->variables['LIVEURL'];
        }
    }
}
