<?php 

class Auth extends Controller{
    public function login(){
        $login = new HTMLElement("login", []);

        $this->addTextToElement($login, ["email" => $this->description["auth-email"], "password" => $this->description["auth-password"]]);

        $auth = new HTMLElement("auth", []);

        $auth->addKids(["data" => $login]);

        echo $this->generatePage($auth);
    }

    public function register(){
        $login = new HTMLElement("register", []);
        $auth = new HTMLElement("auth", []);

        $auth->addKids(["data" => $login]);

        echo $this->generatePage($auth);
    }
}