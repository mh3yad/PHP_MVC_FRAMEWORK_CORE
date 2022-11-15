<?php
namespace app\core;

use app\core\exception\NotFoundException;

class Router
{
    protected array $routes = [];
    public Request $request;
    public Response $response;

    public function __construct(Request $request,Response $response){
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path,$callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback):void
    {
        $this->routes['post'][$path] = $callback;
    }


    public function resolve(): mixed
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if(!$callback){
            throw new  NotFoundException();
        }
        if(is_string($callback)){
            return  Application::$app->view->renderView($callback);
        }
        if(is_array($callback)){
            /**
             * @var Controller $controller;
             */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware){
                $middleware->execute();
            }
        }
        return call_user_func($callback,$this->request,$this->response);
    }


}