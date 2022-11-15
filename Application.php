<?php

namespace app\core;
use app\core\Controller;
use app\core\Router;
use app\core\Request;
use app\models\User;

class Application{


    static string  $ROOT_DIR;
    public string  $userClass = '';
    public static Application $app;
    public string $layout = 'main';

    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public Database $db;
    public Session $session;
    public ?DBModel $user ;

    /**
     * @param $root_dir
     * @param $config
     */
    public function __construct($root_dir,$config){

        self::$ROOT_DIR =  $root_dir;
        self::$app =  $this;

        $this->userClass =  $config['userClass'];
        $this->request= new Request();
        $this->response = new Response();
        $this->router = new Router($this->request,$this->response);
        $this->db = new Database($config['db']);
        $this->session = new Session();

        $primaryValue = $this->session->get('user');
        if($primaryValue){
            $userClass = new $this->userClass();
            $primaryKey = $userClass::primaryKey();
            $this->user = $userClass->findOne([$primaryKey=>$primaryValue]);
        }else{
            $this->user = null;
        }
    }
    public function run(): void
    {
        try {

            echo  $this->router->resolve();
        }catch (\Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo Application::$app->router->renderView('_error',['e' => $e]);
        }
    }

    public function login(User $user):bool{
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user',$primaryValue);
        return  true;
    }
    public function logout(): void
    {
        $this->user = null;
        $this->session->remove('user');
        $this->response->redirect('/');
    }

    public function isGuest():bool{
        return !self::$app->user;
    }




}
