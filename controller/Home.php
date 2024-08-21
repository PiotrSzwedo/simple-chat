<?php 

class Home extends Controller{
    
    public function default(){
        $session = (new SessionService())->getSessionData("userId");

        if (!$session){
            header("Location: /auth");
            return;
        }

        var_dump((new UserService())->findById($session));
    }
}