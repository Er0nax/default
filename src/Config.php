<?php

namespace src;

/**
 * Project Config
 */
class Config
{
    public static array $config = [
        '*' => [
            'useDarkMode' => true,
            'useJQuery' => true,
            'useBootstrap' => true,
            'unsafeEnvVariables' => [ // these variables will be not be added with putenv()
                'DBUSER',
                'DBPASS',
                'DBHOST',
                'DBNAME',
            ],
            'folders' => [
                'templates' => 'templates/', // default folder where twig files are saved
                'error' => 'errors/', // folder inside templates folder, where error pages are saved
                'pages' => 'pages/', // folder inside templates folder, where different pages are saved
                'cache' => '../storage/cache'
            ],
            'rewriteRoutes' => [ // rewrite a route with: from => to
                'start' => 'index',
                'home' => 'index',
                'info' => 'about',
                'message' => 'contact',
                'call' => 'api'
            ],
            'usePreloader' => false,
            'cacheDurations' => [ // in seconds
                'default' => 10,
                'html' => 10,
                'json' => 120
            ],
            'swapper' => [
                'attribute' => 'page'
            ]
        ],
        'dev' => [
            'debugMode' => true,
            'cacheMode' => false,
        ],
        'production' => [
            'debugMode' => false,
            'cacheMode' => true,
        ]
    ];

    /**
     * Builds config and returns it.
     * @param string|null $variable
     * @param mixed|null $fallback
     * @return mixed
     */
    public static function getConfig(string $variable = null, mixed $fallback = null): mixed
    {
        // get the environment
        $environment = getenv('ENVIRONMENT') ?? 'production';

        // get config from cache if exists
        $configFromCache = self::getCache();
        if (!empty($configFromCache) && $environment !== 'dev') {

            // only specific key?
            if ($variable) {
                return $configFromCache[$variable] ?? $fallback;
            }

            // return whole config
            return $configFromCache;
        }

        // add global config
        $config = self::$config['*'];

        // loop through scopes
        foreach (self::$config as $scope => $content) {

            // add global scope and environment scope
            if ($scope == $environment) {
                // content of scope is array?
                if (is_array($content)) {

                    // loop through content
                    foreach ($content as $key => $value) {

                        // add to result
                        $config[$key] = $value;
                    }
                }
            }
        }

        // add custom config variables
        $config['environment'] = $environment;
        $config['isLocal'] = (in_array($_SERVER['REMOTE_ADDR'], ['localhost', '127.0.0.1', '::1']));
        $config['isLoggedIn'] = !empty($_SESSION['user']['id']);
        $config['lang'] = (empty($_SESSION['lang'])) ? getenv('lang') : $_SESSION['lang'];

        // cache allowed?
        if ($config['cacheMode']) {
            self::setCache($config);
        } else {
            self::setCache(false);
        }

        // only specific key?
        if ($variable) {
            return $config[$variable] ?? $fallback;
        }

        return $config;
    }

    /**
     * Returns the unsafe env variables.
     * @return array
     */
    public static function getUnsafeEnvVariables(): array
    {
        return self::$config['*']['unsafeEnvVariables'];
    }

    /**
     * Update the current config.
     * @param array $newConfig
     * @return void
     */
    public static function updateConfig(array $newConfig = [])
    {
        // todo update current config
    }

    /**
     * Set the current config to the session.
     * @param array $config
     * @return void
     */
    private static function setCache(array|bool $config): void
    {
        $_SESSION['cache']['config'] = $config;
    }

    /**
     * Returns config from session or false
     * @return false|mixed
     */
    private static function getCache(): mixed
    {
        if (isset($_SESSION['cache']['config'])) {
            return $_SESSION['cache']['config'];
        }

        return false;
    }
}
