<?php

namespace controllers;

use controllers\Controller;
use Exception;

/**
 * page controller
 */
class PageController extends Controller
{
    public array $info = [];
    private array $default = [
        'name' => 'index',
        'title' => 'Home',
        'headline' => 'Home',
        'subline' => "What's up?",
        'icon' => 'circle',
        'color' => '#fff',
        'isRawPage' => 'false',
        'mustBeLoggedIn' => 'both',
        'hideInHeader' => 'false',
        'showAlways' => 'true',
    ];
    private array $error = [
        'name' => 'error',
        'title' => '404',
        'headline' => '404',
        'subline' => 'Seems like something went wrong.',
        'icon' => 'circle',
        'color' => '#fff',
        'isRawPage' => 'false',
        'mustBeLoggedIn' => 'both',
        'hideInHeader' => 'true',
        'showAlways' => 'false',
    ];
    public array $cssFiles = [];
    public array $jsFiles = [];
    public array $pages = [];

    public function __construct()
    {
        parent::__construct();

        // all pages
        $this->setAllPages();
    }

    /**
     * @param string|null $name
     * @param bool $redirect
     * @return void
     */
    public function setPage(string $name = null, bool $redirect = true): void
    {
        // is empty?
        if (empty($name)) {
            $name = 'index';
        }

        // get a valid page
        $this->info = $this->getPage($name, $redirect);

        // set files
        $this->setCssFiles();
        $this->setJsFiles();
    }

    /**
     * @param string|null $name
     * @param bool $redirect
     * @return array
     */
    public function getPage(string $name = null, bool $redirect = true): array
    {
        // check if rules were passed
        if (!$this->followsRules($name, $redirect)) {
            // show homepage
            return $this->default;
        }

        // check if page exists
        if (!$this->exists($name)) {
            $this->error['headline'] = '404';
            $this->error['subline'] = 'Page not found!';
            return $this->error;
        }

        // set page
        $entry = new Entry();
        $result = $entry->columns(['pages' => ['*']])
            ->tables(['pages'])
            ->where(['pages' => [['name', $name]]])
            ->one();

        if (!empty($result)) {
            return $result;
        }

        return $this->default;
    }

    /**
     * @return void
     */
    private function setCssFiles(): void
    {
        // empty array
        $cssFiles = [];
        $customCssFiles = [];

        // add default css?
        $addDefaults = true;

        switch ($this->info['name']) {
            case 'index':
                // custom files
                $customCssFiles[] = 'assets/css/styles.css';
                break;
            case 'dkfksdjfk':
                $addDefaults = false;
                break;
        }

        // default css
        if ($addDefaults) {
            $cssFiles[] = 'assets/vendor/bootstrap/css/bootstrap.css';
            $cssFiles[] = 'assets/vendor/sweetalert/css/sweetalert2.min.css';
            $cssFiles[] = 'assets/site/css/lines.css';
        }

        // add custom files (because of order)
        $this->cssFiles = array_merge($cssFiles, $customCssFiles);
    }

    /**
     * sets js files
     * @return void
     */
    private function setJsFiles(): void
    {
        // empty array
        $jsFiles = [];
        $customJsFiles = [];

        // add default css?
        $addDefaults = true;

        switch ($this->info['name']) {
            case 'index':
                // custom files
                $jsFiles[] = ['url' => 'assets/js/scripts.js', 'type' => 'application/javascript'];
                break;
            case 'dkjfkdsjf':
                $addDefaults = false;
                break;
        }

        // default css
        if ($addDefaults) {
            $jsFiles[] = ['url' => 'assets/vendor/jquery/js/jquery.js', 'type' => 'application/javascript'];
            $jsFiles[] = ['url' => 'assets/vendor/bootstrap/js/bootstrap.bundle.js', 'type' => 'application/javascript'];
            $jsFiles[] = ['url' => 'assets/vendor/sweetalert/js/sweetalert2.min.js', 'type' => 'application/javascript'];
            $jsFiles[] = ['url' => 'assets/site/js/basics.js', 'type' => 'module'];
            $jsFiles[] = ['url' => 'assets/site/js/swapper.js', 'type' => 'module'];
        }

        $this->jsFiles = array_merge($jsFiles, $customJsFiles);
    }

    /**
     * @param string|null $_page
     * @param bool $redirect
     * @param bool $forHeader
     * @return bool
     */
    public function followsRules(string $_page = null, bool $redirect = true, bool $forHeader = false): bool
    {
        // get page
        $entry = new Entry();
        $page = $entry->columns(['pages' => ['*']])
            ->tables('pages')
            ->where(['pages' => [['name', $_page]]])
            ->one();

        // page found?
        if (empty($page)) {
            // redirect?
            if ($redirect) $this->redirect('error');
            return false;
        }

        if ($forHeader) {
            // hide the page in the header?
            if ($page['hideInHeader'] == 'true') {
                if ($redirect) $this->redirect('error');
                return false;
            }
        }

        // always show in the sidebar?
        if ($page['showAlways'] == 'true') {
            return true;
        }

        // must be logged in?
        if ($page['mustBeLoggedIn'] == 'true') {
            if (!$this->isLoggedIn) {
                if ($redirect) $this->redirect('login');
                return false;
            }
        }

        // must not be logged in?
        if ($page['mustBeLoggedIn'] == 'false') {
            if ($this->isLoggedIn) {
                if ($redirect) $this->redirect('index');
                return false;
            }
        }

        // is active?
        if ($page['isActive'] == 'false') {
            if ($redirect) {
                $this->redirect('error');
            }
        }

        // check if user is not logged in but wants to see profile
        if (empty($_SESSION['user']['id'])) {
            if ($_page == 'profile') {
                if ($forHeader) {
                    return false;
                }

                if (!empty($_GET['id'])) {
                    return true;
                } else {
                    if ($redirect) $this->redirect('login');
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param $_page
     * @return bool
     */
    public function exists($_page): bool
    {
        // check if the file exists
        if (!file_exists($_page . '.php')) {
            return false;
        }

        // check if the content exists
        if (!file_exists('../views/content/' . $_page . '/index.view.php')) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    private function setAllPages(): void
    {
        // get all pages
        $entry = new Entry();
        $pages = $entry->columns(['pages' => ['*']])
            ->tables(['pages'])
            ->order('pages.index ASC')
            ->all();

        // check if all pages also exist in project
        foreach ($pages as $page) {
            if ($this->exists($page['name'])) {
                $this->pages[] = $page;
            }
        }
    }
}