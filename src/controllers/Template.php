<?php

namespace src\controllers;

use src\Config;
use src\helpers\CacheHelper;
use src\helpers\FileHelper;
use src\services\Twig\Extension;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Template Controller
 */
class Template extends Main
{
    private string $page = 'index';
    private array $params = [];

    /**
     * Constructor
     * @param string $page
     * @param array $params
     */
    public function __construct(string $page = 'index', array $params = [])
    {
        $this->page = $page;
        $this->params = $params;
    }

    /**
     * Returns the file given by this->page
     * @return string
     */
    private function getPageFile(): string
    {
        // get pages folder from config
        $pagesFolder = Config::getConfig('folders')['pages'] ?? 'pages/';

        // check if page exists in pages folder
        $fileByPage = $pagesFolder . $this->page . '.twig';
        if (!$this->checkIfFileExists($fileByPage)) {
            return $this->getErrorPage('404');
        }

        return 'layout/site.twig';
    }

    /**
     * @param string $file
     * @return bool
     */
    private function checkIfFileExists(string $file = null): bool
    {
        if (empty($file)) {
            return false;
        }

        $templateFolder = Config::getConfig('folders')['templates'] ?? 'templates/';
        return FileHelper::exist($templateFolder . $file);
    }

    /**
     * Returns the error page given by the type
     * @param string $type
     * @return string
     */
    private function getErrorPage(string $type): string
    {
        $errorFolder = Config::getConfig('folders')['error'] ?? 'errors/';
        return $errorFolder . $type . '.twig';
    }

    /**
     * Renders a template.
     */
    public function renderTemplate(string $file = null): void
    {
        // is cached?
        $this->getTemplateFromCache();

        // check if custom file exists
        if (!$this->checkIfFileExists($file)) {
            // if not then show by page
            $file = $this->getPageFile();
        }

        // get debug mode
        $debugMode = Config::getConfig('debugMode', false);
        $templateFolder = BASE_PATH . Config::getConfig('folders')['templates'] ?? 'templates/';

        $cache = false;
        if (Config::getConfig('cacheMode', false)) {
            $cache = Config::getConfig('folders')['cache'] ?? '../storage/cache';
        }

        // new loader + twig
        $loader = new FilesystemLoader($templateFolder);
        $twig = new Environment($loader, [
            'debug' => $debugMode,
            'cache' => $cache,
        ]);

        // add debug extension
        if ($debugMode) {
            $twig->addExtension(new DebugExtension());
        }

        // add custom twig functions
        $TwigFunctions = new Extension();
        $twig->addExtension($TwigFunctions);

        // add globals
        $twig->addGlobal('session', $_SESSION);

        try {
            $template = $twig->render($file, [
                'config' => Config::getConfig(),
                'page' => $this->page,
                'params' => $this->params,
                'module' => $this->getModule(),
            ]);
        } catch (\Exception $e) {
            exit('Could not create a new twig environment.');
        }

        // save in cache
        $this->setTemplateToCache($template);

        echo $template;
    }

    /**
     * Echos the cached template if exists.
     * @return void
     */
    private function getTemplateFromCache(): void
    {
        if (Config::getConfig('cacheMode', false)) {
            $arrayOfPageAndParams['page'] = $this->page;
            $arrayOfPageAndParams['params'] = $this->params;

            $template = CacheHelper::getCache($arrayOfPageAndParams);

            if (!empty($template)) {
                echo $template;
                exit();
            }
        }
    }

    /**
     * save the template in cache if cache mode enabled.
     * @param string $page
     * @param array $params
     * @return void
     */
    private function setTemplateToCache(mixed $template): void
    {
        if (Config::getConfig('cacheMode', false)) {
            $arrayOfPageAndParams['page'] = $this->page;
            $arrayOfPageAndParams['params'] = $this->params;

            $hash = CacheHelper::generateHash($arrayOfPageAndParams);
            CacheHelper::setCache($template, $hash);
        }
    }

    /**
     * Returns the module for a page if exists or the main module.
     * @return mixed|main
     */
    private function getModule(): mixed
    {
        // check if page specific module exists
        if (FileHelper::exist('src/modules/' . $this->page . '.php')) {

            // call class
            $moduleClass = '\\src\\modules\\' . $this->page;

            // Check if class exists
            if (class_exists($moduleClass)) {
                return new $moduleClass();
            }
        }

        // return default main module
        return new \src\modules\main();
    }

    private function getPageInfo()
    {
        $entry = new Entry();
    }
}