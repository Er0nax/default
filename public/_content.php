<?php
/** This file is just here to communicate between the frontend and the backend (js and php)*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** content helper class */
class Content
{
    private ?string $page = null;
    private ?string $param = null;
    private string $defaultPage = 'error';

    /**
     * constructor
     * @param string|null $page
     */
    public function __construct(string $page = null)
    {
        $this->setPage($page);
    }

    /**
     * sets the page to class
     * @param string|null $page
     * @return void
     */
    private function setPage(string $page = null): void
    {
        $this->page = $this->getValidPage($page);
    }

    /**
     * returns the final page
     * @param string|null $_page
     * @return string
     */
    private function getValidPage(string $_page = null): string
    {
        if (empty($_page)) {
            return $this->defaultPage;
        } else {
            $this->page = $_page;
        }

        if (!$this->pageExists()) {
            return $this->defaultPage;
        }

        if (!$this->pageContentExists()) {
            return $this->defaultPage;
        }

        if (!$this->pageModuleExists()) {
            return $this->defaultPage;
        }

        return $_page;
    }

    /**
     * @return string|null
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    /**
     * @return string|null
     */
    public function getParam(): ?string
    {
        return '';
    }

    /**
     * returns boolean whether a page exists or not
     * @return bool
     */
    private function pageExists(): bool
    {
        return file_exists($this->page . '.php');
    }

    /**
     * returns boolean whether the page content exists ot not
     * @return bool
     */
    private function pageContentExists(): bool
    {
        return file_exists('../views/content/' . $this->page . '/index.view.php');
    }

    /**
     * returns boolean whether a page module exists or not
     * @return bool
     */
    private function pageModuleExists(): bool
    {
        return file_exists('../app/modules/site/' . $this->page . '.php');
    }
}

// create new instance
$content = new Content($_GET['page']);

// include files
include('../app/config/init.php');
include('../app/modules/site/' . $content->getPage() . '.php' . $content->getParam());
include('../views/content/' . $content->getPage() . '/index.view.php' . $content->getParam());