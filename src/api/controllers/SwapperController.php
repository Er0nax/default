<?php

namespace src\api\controllers;

use src\controllers\Template;

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

    public function actionGetContent(): void
    {
        $page = $this->params['page'] ?? 'index';
        $params = $this->params['params'] ?? [];

        $template = new Template($page, $params);
        $response = $template->renderApiTemplate();

        $content = [
            'content' => $response['content'],
            'msg' => $response['msg'] ?? null,
        ];

        $this->render($content, $response['status'], [
            'cache' => true
        ]);
    }
}