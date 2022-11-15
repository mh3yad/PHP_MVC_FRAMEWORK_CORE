<?php
namespace mh3yad\phpmvc;

use mh3yad\phpmvc\exception\NotFoundException;

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

    public function renderOnlyView($view,$params = []):string{
        foreach ($params as $key => $value){
            $$key = $value;
        }
        ob_start();
        require_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

    public function layoutContent(): string
    {
        $layout = Application::$app->layout;
        if(Application::$app->controller)
            $layout = Application::$app->controller->layout;
        ob_start();
        require_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    public function renderView($callback,$params = []):string{

        $layout = $this->layoutContent();
        $viewContent = $this->renderOnlyView($callback,$params);
        return  str_replace("{{content}}",$viewContent,$layout);
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
            return  $this->renderView($callback);
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