<?php

class PageGenerator{
    public function generatePage($body, $style = null, $scripts = null, $header = null, $footer = true, $nav = true){
        $index = new HTMLElement("index", []);

        if ($body){
            $index->addKid("body", $body);
        }

        echo $index->render();
    }
}