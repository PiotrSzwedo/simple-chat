<?php 

class Router{
    protected $controller;
    protected $action;
    protected $parameters;
    protected DatabaseService $db;
    protected $fileConfig;

    public function __construct(DatabaseService $database, $fileConfig){
        $this->fileConfig = $fileConfig;
        $this->db = $database;
        $this->praseUri();
        $this->loadModule();
    }

    public function praseUri(){
        $parsedUrl = explode('/', $_SERVER["REQUEST_URI"]);

        array_shift($parsedUrl);

        $this->controller = array_shift($parsedUrl);
        $this->action = str_replace("-", "_", array_shift($parsedUrl));
        $this->parameters = str_replace("-", "_", $parsedUrl);
    }

    public function loadModule()
    {
        
        $controller = $this->controller;

        if (class_exists(ucfirst($controller), true) || class_exists($controller, true) || class_exists(strtolower($controller), true))
            $this->controller = $this->induceController($controller);
        else if ($controller != "php")
            $this->controller = $this->induceController("Home");
    }

    private function induceController($controllerName){
        return new $controllerName(
            $this->action, 
            $this->parameters, 
            new UserService($this->db), 
            new MessageService($this->db), 
            new SessionService(), 
            new ConvertService(), 
            $this->db,
            new FileService($this->fileConfig)
        );
    }
}