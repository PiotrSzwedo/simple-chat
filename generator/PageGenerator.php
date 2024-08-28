<?php

class PageGenerator{

    protected DatabaseService $database;

    public function __construct(){
        $this->database = new DatabaseService();
    }
    public function generatePage($body, array $style = array(), array $scripts = array(), $nav = false, $footer = false){
        $index = new HTMLElement("index", []);

        if ($body){
            $index->addKid("body", $body);
        }

        $index->addKid("style", $this->generateStyles($style));
        $index->addKid("script", $this->generateScript($scripts));

        echo $index->render();
    }

    public function addTextToElement(HTMLElement $element, array $texts){
        foreach ($texts as $key=>$text){
            $element->addKid($key, new HTMLElement("element", ["element" => $text]));
        }
    }

    public function generateStyles($arrayOfStyleFiles){
        $arrayOfStyleFiles[] = "default";

        return $this->generateHTMLFromArray($arrayOfStyleFiles, "style", "style");
    }
    
    public function generateScript($arrayOfScriptsFilesNames){
        $arrayOfScriptsFilesNames[] = "nav";

        return $this->generateHTMLFromArray($arrayOfScriptsFilesNames, "script", "js");
    }

    private function generateHTMLFromArray($array, $templateName, $filedName){
        foreach ($array as $filed){

            if (is_array($filed)){
                $filed = implode($filed);
            }

            $element = new HTMLElement($templateName, [$filedName => $filed]);
            $elements[] = ["element" => $element->render()];
        }

        return new HTMLMultiElement("element", $elements);
    }

    public function connectElements(array $arrayOfHtmlElements){
        if (!empty($arrayOfHtmlElements)){

            foreach ($arrayOfHtmlElements as $element) {
                $elements[] = ["element" => $element->render()];
            }

            $content = new HTMLMultiElement('element', $elements);

            return $content;
        }
    }
}