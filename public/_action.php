<?php
/** this file returns repsonses for the ajax requests */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../app/config/init.php');
global $app;

use JetBrains\PhpStorm\NoReturn;

/**
 * Action class
 */
class _action extends \controllers\Controller
{
    /**
     * @var string
     */
    public string $action = 'unknown';

    public bool $showswal = true;

    /**
     * @var array
     */
    public array $post = [];

    /**
     * @var array
     */
    public array $files = [];

    /**
     * @var string[]
     */
    public array $possibleActions = [
        'updateVariable',
    ];

    /**
     * constructor
     */
    public function __construct(array $post = [], array $get = [])
    {
        parent::__construct();

        if (!empty($post)) {
            $this->post = $post;
        } elseif (!empty($get)) {
            $this->post = $get;
        }

        if (!empty($_FILES)) {
            $this->files = $_FILES;
        }

        $this->getSettings();
        $this->getAction();
    }

    /**
     * @return void
     */
    private function getSettings(): void
    {
        // is post given
        if (!empty($this->post)) {

            // check for specific settings
            if (!empty($this->post['showswal'])) {
                if ($this->post['showswal'] == 'false') {
                    $this->showswal = false;
                }
            }
        }
    }

    /**
     * checks if an action exists
     * @return void
     */
    private function getAction(): void
    {
        // check if action post was given
        if (empty($this->post['action'])) {
            if ($this->showswal) {
                $this->render('The action you are trying to execute does not exist.');
            } else {
                return;
            }
        }

        // set action
        $this->action = $this->post['action'];

        // check if action post exists
        if (!$this->isValid($this->action)) {
            if ($this->showswal) {
                $this->render('The action you are trying to execute is not possible.');
            } else {
                return;
            }
        }

        $this->callAction();
    }

    /**
     * returns bool whether an action exists or not
     * @param string $action
     * @return bool
     */
    private function isValid(string $action): bool
    {
        // check if action is in list
        if (!in_array($action, $this->possibleActions)) {
            return false;
        }

        // check if action is callable
        $functions = get_class_methods($this);

        if (!in_array($action, $functions)) {
            return false;
        }

        return true;
    }

    /**
     * calls the action as a function
     * @return void
     */
    private function callAction(): void
    {
        // get action
        $action = $this->action;

        // call it
        $this->$action();
    }

    /**
     * @return void
     */
    private function updateVariable(): void
    {
        $this->render('Not available yet.');
    }
}

$action = new _action($_POST, $_GET);