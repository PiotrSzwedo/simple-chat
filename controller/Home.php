<?php 

class Home extends Controller{
    
    public function default(){
        $userId = (new SessionService())->getSessionData("userId");

        if (!$userId){
            header("Location: /auth");
            return;
        }else{
            header("Location: /chat");
        }
    }
}