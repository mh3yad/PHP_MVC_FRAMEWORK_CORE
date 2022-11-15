<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = 'main';
    public string $action = '';

    /**
     * @var BaseMiddleware[]
     */
    public array $middlewares = [];

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
    public function setLayout($layout): string
    {
        return $this->layout = $layout;
    }
    public function render($view,$params=[]): string
    {
        return Application::$app->view->renderView($view,$params);
    }

    public function registerMiddleware(BaseMiddleware $baseMiddleware){
        $this->middlewares[] = $baseMiddleware;
    }
}