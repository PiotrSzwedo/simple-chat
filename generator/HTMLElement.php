<?php

class HTMLElement implements Renderable{
    protected $template;
    protected $data;
    protected $kids = [];

    public function __construct($template, $data){
        $this->template = $template;
        $this->data = $data;
    }

    public function addKid(string $kidName, $kidData){
        $this->kids[$kidName] = $kidData;
    }

    // [kidName => kidData, ...];
    public function addKids(array $kids){
        foreach($kids as $key=>$data){
            $this->kids[$key] = $data;
        }
    }

    public function renderKids()
    {
        foreach ($this->kids as $name=>$kid)
        {
            $this->data[$name] = $kid->render();
        }
    }

    public function render(): string
    {
        $this->renderKids();
        $generator = new HTMLGenerator($this->template, $this->data);
        $generator->loadTemplate();
        return $generator->render();
    }
}