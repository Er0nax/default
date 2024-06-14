<?php

namespace src\api\controllers;

use src\api\controllers\MainController;

/**
 * Default Controller for any API Request.
 */
class DefaultController extends MainController
{
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function actionIndex()
    {
        $this->render('index action from default controller.');
    }
}