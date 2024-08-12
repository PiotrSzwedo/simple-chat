<?php 

class Router{
    protected $controller;
    protected $action;
    protected $parameters;

    public function __construct(){
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
            $this->controller = new $controller($this->action, $this->parameters);
        else
            $this->controller = new Home($this->action, $this->parameters);
    }
}