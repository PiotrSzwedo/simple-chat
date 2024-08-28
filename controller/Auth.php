<?php 

class Auth extends Controller{
    public function default(){
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
                    $this->generateAuthForm(false, "error");
                    return;
                }
            }else if ($_POST["action"] === "register"){
                if ($authService->register($email, $_POST["name"], $password)){
                    header("Location: /auth");
                    return;
                }else{
                    $this->generateAuthForm(true, "error");
                    return;
                }
            }
        }

        $this->generateAuthForm(false, "");
    }

    private function generateAuthForm($register, $class){
        $auth = new HTMLElement("auth", []);
        
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