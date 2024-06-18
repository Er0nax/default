<?php

namespace src\api\controllers;

class SwapperController extends MainController
{
    /**
     * Constructor
     * @param $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function actionGetContent()
    {
        $this->render('hi!', 200, [
            'cache' => true
        ]);
    }
}