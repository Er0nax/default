<?php
session_start();

// Define path constants
use modules\App;

define('BASE_PATH', dirname(__DIR__) . '/');
define('VENDOR_PATH', BASE_PATH . '/vendor');
define('ASSET_PATH', BASE_PATH . '/assets');

// Load Composer's autoloader
require VENDOR_PATH . '/autoload.php';

// Define additional PHP constants
define('BLAZE_ENVIRONMENT', getenv('ENVIRONMENT') ?? 'production');

// include all classes
spl_autoload_register(function ($class_name) {
    include('../' . str_replace('\\', '/', $class_name) . '.php');
});

// load and run flux
$app = new App();
$app->run();

