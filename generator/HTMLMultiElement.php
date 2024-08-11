<?php
class HTMLMultiElement implements Renderable
{
    protected $templateName;
    protected $data = [];

    public function __construct($templateName, $data)
    {
        $this->templateName = $templateName;
        $this->data = $data;
    }

    public function render(): string 
    {
        $generator = new HTMLGenerator('',[]);
        $generator->loadTemplate($this->templateName);
        $result = '';
        
        foreach($this->data as $row)
        {
            $generator->setData($row);
            $result .= $generator->render();
        }

        return $result;
    }
}