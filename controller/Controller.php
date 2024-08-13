<?php 

abstract class Controller extends PageGenerator{
    protected $description;

    public function __construct($action, $parameters){
        parent::__construct();

        $this->description = $this->description = (new ConvertService)->convertToStringByKey($this->database->get("SELECT name, element FROM description"), "name", "element")[0];
        
        if(empty($action)){
            $this->default();
        }else{

            if (!method_exists($this, $action)){
                $this->error404();
                return;
            }
            
            $reflectionMethod = new ReflectionMethod(get_class($this), $action);
            $requiredParams = $reflectionMethod->getNumberOfRequiredParameters();
            $params = $reflectionMethod->getNumberOfParameters();

            if (
                    $reflectionMethod->isPublic() && 
                    count($parameters) >= $requiredParams &&
                    count($parameters) <= $params
                ) {
                $this->$action(...$parameters);
            }
            else{
                $this->error404();
            }
        }
    }

    public function default(){
        $this->error404();
    }

    private function error404(){
        echo "error 404";
    }
}