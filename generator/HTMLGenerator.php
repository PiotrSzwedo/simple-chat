<?php

class HTMLGenerator
{
    protected $template;
    protected $data;

    public function __construct($templateName, $data)
    {
        $this->template = $templateName;
        $this->data = $data;
    }

    public function loadTemplate()
    {
        $filePath = __DIR__ . "/../template/$this->template.tpl.html";
        
        if (file_exists($filePath)) {
            $this->template = file_get_contents($filePath);
        }
    }

    public function render()
    {
        $result = $this->template;

        foreach ($this->data as $key => $record) {
            if (is_array($record) && key_exists($key, $record)) {
                $result = str_replace("<!--$key-->", $record, $result);
            } else {
                $result = str_replace("<!--$key-->", $record, $result);
            }
        }

        $result = preg_replace('/<!--.*?-->/', '', $result);
        return $result;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}