<?php 

class Auth extends Controller{
    public function default(){
        $authService = new AuthService();
        
        if (!empty($_POST["action"])){
            
            $email = $_POST["email"];
            $password = $_POST["password"];
            
            if ($_POST["action"] === "login"){
                if ($authService->login($email, $password)){
                    return;
                }
            }else if ($_POST["action"] === "register"){
                if ($authService->register($email, $_POST["name"], $password)){
                    return;
                }
            }
        }
        
        $auth = new HTMLElement("auth", []);
        $this->addTextToElement($auth, [
            "button" => $this->description["auth-login-button"],
            "button2" => $this->description["auth-register-button"], 
            "email" => $this->description["auth-email"],
            "name" => $this->description["auth-name"],  
            "password" => $this->description["auth-password"]]);

        $this->addTextToElement($auth, ["method" => "post", "action" => $_SERVER["REQUEST_URI"]]);

        echo $this->generatePage($auth);
    }
}