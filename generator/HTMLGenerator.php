<?php

class HTMLGenerator
{
    private $template = '';
    private $data = [];

    public function __construct($template, $data)
    {
        $this->data=$data;
        $this->template=$template;
    }

    public function loadTemplate($filename){
        $filePath = __DIR__."/../template/$filename.tpl.html";
        if(is_file($filePath))
            $this->template = file_get_contents($filePath);
        else
            $this->template="";
    }

    public function setData($data){
        $this->data = $data;
    }

    public function render(){
        $result=$this->template;
        foreach($this->data as $key=>$record){
            if (is_array($record) && key_exists($key, $record)){
                $result=str_replace("<!--$key-->",$record,$result);
            }else{
                $result=str_replace("<!--$key-->",$record,$result);
            }
        }
        $result = preg_replace('/<!--.*?-->/','',$result);
        return $result;
    }


}