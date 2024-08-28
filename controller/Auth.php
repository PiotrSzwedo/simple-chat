<?php 

class Auth extends Controller{
    public function default(){
        $this->generateAuthForm(false, "");
    }

    public function error($page = "login"){
        if ($page = "register"){
            $this->generateAuthForm(true, "error");
        }else{
            $this->generateAuthForm(false, "error");
        }
    }

    private function generateAuthForm($register, $class){
        $auth = new HTMLElement("auth", []);
        $authService = new UserService();
        $session = new SessionService();
        
        if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST["action"])){
            
            $email = $_POST["email"];
            $password = $_POST["password"];
            
            if ($_POST["action"] === "login"){
                if ($authService->login($email, $password)){
                    $session->createSession($authService->getIdByEmail($email), "userId");
                    header("Location: /");
                    return;
                }else{
                    header("Location: /auth/error/login");
                }
            }else if ($_POST["action"] === "register"){
                if ($authService->register($email, $_POST["name"], $password)){
                    header("Location: /auth");
                    return;
                }else{
                    header("Location: /auth/error/register");
                }
            }
        }

        if ($register){
            $this->addTextToElement($auth, ["open2" => "open $class"]);
        }else{
            $this->addTextToElement($auth, ["open" => "open $class"]);
        }
        
        
        $this->addTextToElement($auth, [
            "button" => $this->description["auth-login-button"],
            "button2" => $this->description["auth-register-button"], 
            "email" => $this->description["auth-email"],
            "name" => $this->description["auth-name"],  
            "password" => $this->description["auth-password"]]);

        $this->addTextToElement($auth, ["method" => "post", "action" => $_SERVER["REQUEST_URI"]]);

        echo $this->generatePage($auth, array(), ["auth"]);
    }
}