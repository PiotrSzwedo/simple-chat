<?php 

class Auth extends Controller{
    public function login(){
        $login = new HTMLElement("login", []);
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