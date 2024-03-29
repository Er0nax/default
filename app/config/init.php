<?php

// start session if it doesn't exist already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include database connection
include('../app/config/env.php');
include('../app/config/database.php');

$env = new env();
$db = new database();

// include configs
include('../app/config/settings.php');

// include controllers
include('../app/controllers/MainController.php');
include('../app/controllers/EntryController.php');
include('../app/controllers/TranslationController.php');
include('../app/controllers/PageController.php');

// include helpers
include('../app/helpers/SiteHelper.php');

// include modules
include('../app/modules/site/Main.php');

/**
 * init of website
 */
class init
{
    public database $database;
    public env $env;
    public settings $settings;
    public \helpers\SiteHelper $helper;
    public \controllers\PageController $page;
    public \controllers\Translation $translation;

    public function __construct(env $env, database $db)
    {
        $this->env = $env;
        $this->database = $db;
        $this->settings = new settings();
        $this->helper = new \helpers\SiteHelper();
        $this->translation = new \controllers\Translation();
        $this->page = new \controllers\PageController();
    }
}

// start init
$app = new init($env, $db);

// global funtions
function t(string $value, string $category = 'site'): string
{
    global $app;
    return $app->translation->getTranslation($value, $category);
}
