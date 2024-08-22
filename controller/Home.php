<?php 

class Home extends Controller{
    
    public function default(){
        $session = (new SessionService())->getSessionData("userId");

        if (!$session){
            header("Location: /auth");
            return;
        }

        $chat = new HTMLElement("home", []);

        $this->generatePage($chat);
    }
}