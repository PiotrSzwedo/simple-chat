<?php

class CookieService{
    
    public function saveChose($chose){
        $expire = time() + 315360000;
        setcookie("chose", $chose, $expire, "/", "", true, true);
    }
    
    public function getChose(){
        if (key_exists("chose", $_COOKIE)){
            return $_COOKIE["chose"];
        }else return null;
    }

    public function getData($name){
        if (key_exists($name, $_COOKIE)){
            return $_COOKIE[$name];
        }else return null;
    }

    public function setData($name, $data, $expire){
        $expire += time();
        setcookie($name, $data, $expire, "/", "", true, true);
    }
}