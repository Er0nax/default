<?php

namespace src\api;

use src\api\controllers\MainController;
use src\Config;
use src\helpers\CacheHelper;
use src\helpers\FileHelper;
use src\helpers\ParseHelper;

/**
 * Api start
 */
class Api extends MainController
{
    private ?string $controllerName = null;
    private ?string $controllerAction = null;

    /**
     * Constructor
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;

        $this->callAction();
    }

    /**
     * Call the action inside the given controller.
     * @return void
     */
    private function callAction(): void
    {
        // check if controller param is given
        if (empty($this->params[0])) {
            $this->params[0] = 'default';
        }

        // check if controller action is given
        if (empty($this->params[1])) {
            $this->params[1] = 'index';
        }

        // set name and action
        $this->controllerName = $this->getControllerNameByParam();
        $this->controllerAction = $this->getControllerActionByParam();

        // remove first and second key from params as they are just the controller and action...
        array_shift($this->params);
        array_shift($this->params);

        // api can be cached?
        if (Config::getConfig('api')['cache']) {
            // check if there is cached content with hash of params
            $cachedResponse = CacheHelper::getCache($this->params);
            if (!empty($cachedResponse)) {
                $this->render($cachedResponse, 200, [
                    'cached' => true
                ]);
            }
        }

        // check for controller (class)
        if (!$this->checkIfControllerExists()) {
            $this->render('Could not find any matching controller!');
        }

        // create new class
        $className = "\\src\\api\\controllers\\$this->controllerName";
        $classObject = new $className($this->params);

        // check for controller
        if (!$this->checkIfActionExists($classObject)) {
            $this->render('Could not find any matching actions!');
        }

        // call action
        $actionName = $this->controllerAction;
        $classObject->$actionName();
    }

    /**
     * Returns boolean whether a controller as a file exists or not.
     * @return bool
     */
    private function checkIfControllerExists(): bool
    {
        // return if the file exists or not
        return FileHelper::exist('src/api/controllers/' . $this->controllerName . '.php');
    }

    /**
     * Returns boolean whether a function exists in the call or not
     * @param object $classObject
     * @return bool
     */
    private function checkIfActionExists(object $classObject): bool
    {
        return method_exists($classObject, $this->controllerAction);
    }

    /**
     * Returns the controller name
     * @return string
     */
    private function getControllerNameByParam(): string
    {
        $name = ParseHelper::escapeString($this->params[0], '/[^a-zA-Z0-9\-]/');
        return ucfirst($name) . 'Controller';
    }

    /**
     * Returns the action name
     * @return string
     */
    private function getControllerActionByParam(): string
    {
        // parse name
        $fullName = strtolower(ParseHelper::escapeString($this->params[1], '/[^a-zA-Z0-9\-]/'));

        // Split the string by dashes
        $parts = explode('-', $fullName);

        // Capitalize each part and concatenate them
        $name = 'action';
        foreach ($parts as $part) {
            $name .= ucfirst($part);
        }

        return $name;
    }
}
