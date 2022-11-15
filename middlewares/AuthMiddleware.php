<?php

namespace mh3yad\phpmvc\middlewares;

use mh3yad\phpmvc\Application;
use mh3yad\phpmvc\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];
    public function __construct(array $actions=[]){
        $this->actions = $actions;
    }

    public function execute()
    {
       if(Application::$app->isGuest()){
            if(empty($this->actions) || in_array(Application::$app->controller->action,$this->actions)){
                throw new ForbiddenException();
            }
       }
    }
}