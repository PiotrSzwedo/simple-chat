<?php

class PageGenerator{

    protected DatabaseService $database;

    public function __construct(){
        $this->database = new DatabaseService();
    }
    public function generatePage($body, $style = null, $scripts = null, $header = null, $footer = true, $nav = true){
        $index = new HTMLElement("index", []);

        if ($body){
            $index->addKid("body", $body);
        }

        echo $index->render();
    }

    public function addTextToElement(HTMLElement $element, array $texts){
        foreach ($texts as $key=>$text){
            $element->addKid($key, new HTMLElement("element", ["element" => $text]));
        }
    }
}