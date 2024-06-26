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
    private array $info = [];

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
    private function getPageFile(bool $isApi = false): string
    {
        // get pages folder from config
        $pagesFolder = Config::getConfig('folders')['pages'] ?? 'pages/';

        // check if page exists in pages folder
        $fileByPage = $pagesFolder . $this->page . '.twig';

        // check if page exists in db
        if (!$this->checkIfPageExistsInDB()) {
            return $this->getErrorPage('404', $isApi);
        }

        // check if file exists locally
        if (!$this->checkIfFileExists($fileByPage)) {
            return $this->getErrorPage('500', $isApi);
        }

        if ($isApi) {
            return $pagesFolder . $this->page . '.twig';
        } else {
            return 'layout/site.twig';
        }
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
     * @param bool $isApi
     * @return string
     */
    private function getErrorPage(string $type, bool $isApi = false): string
    {
        $this->page = $type;
        $errorFolder = Config::getConfig('folders')['error'] ?? 'errors/';

        if ($isApi) {
            return $errorFolder . $type . '.twig';
        }

        return $errorFolder . '_entry.twig';
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

        $entry = $this->getPageEntryInfoFromDB();
        $twig = $this->getNewTwig($entry);

        try {
            $template = $twig->render($file, [
                'config' => Config::getConfig(),
                'page' => $this->page,
                'params' => $this->params,
                'module' => $this->getModule(),
                'entry' => $entry,
                'isApi' => false,
            ]);
        } catch (\Exception $e) {
            exit('Could not create a new twig environment.');
        }

        // save in cache
        $this->setTemplateToCache($template);

        echo $template;
    }

    /**
     * Returns the content for the api.
     * @return array
     */
    public function renderApiTemplate(): array
    {
        $file = $this->getPageFile(true);

        $entry = $this->getPageEntryInfoFromDB();
        $twig = $this->getNewTwig($entry);

        try {
            $template = $twig->render($file, [
                'config' => Config::getConfig(),
                'page' => $this->page,
                'params' => $this->params,
                'module' => $this->getModule(),
                'entry' => $entry,
                'isApi' => true,
            ]);
        } catch (\Exception $e) {
            return [
                'status' => 500,
                'content' => $e->getMessage(),
                'entry' => $entry,
                'msg' => 'Could not create a new twig environment.'
            ];
        }

        // return the template
        return [
            'status' => 200,
            'entry' => $entry,
            'content' => $template,
        ];
    }

    /**
     * @param array $entry
     * @return Environment
     */
    private function getNewTwig(array $entry): Environment
    {
        // get debug mode
        $debugMode = Config::getConfig('debugMode', false);
        $templateFolder = BASE_PATH . Config::getConfig('folders')['templates'] ?? 'templates/';

        $cache = false;
        if (Config::getConfig('cacheMode', false)) {
            //$cache = Config::getConfig('folders')['cache'] ?? '../storage/cache';
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
        $TwigFunctions = new Extension($entry);
        $twig->addExtension($TwigFunctions);

        // add globals
        $twig->addGlobal('session', $_SESSION);

        return $twig;
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

    /**
     * Returns the info about a page as array
     * @return array|bool|string
     */
    private function getPageEntryInfoFromDB(): bool|array|string
    {
        $entry = new Entry();
        $entry->columns(['pages' => [
            '*'
        ]])->tables(['pages'])
            ->where(['pages' => [
                ['name', $this->page]
            ]]);

        $page = $entry->one();

        // add params
        $page['params'] = $this->params;

        return $page;
    }

    /**
     * @return bool|string
     */
    private function checkIfPageExistsInDB(): bool|string
    {
        $entry = new Entry();
        $entry->columns(['pages' => ['id']])
            ->tables(['pages'])
            ->where(['pages' => [
                ['name', $this->page],
                ['active', true]
            ]]);

        return $entry->exists();
    }
}