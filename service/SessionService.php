<?php

class SessionService{
    
    public function __construct(){
        if(session_status() !== PHP_SESSION_ACTIVE)
            session_start();
    }

    public function createSession($data, $sessionName){
        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start(); 
        }

        $_SESSION[$sessionName] = $data;
    }
    
    public function getField($field, $sessionName){
        if(session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION[$sessionName])) {
            return null;
        }

        $data = $_SESSION[$sessionName];
        return isset($data[$field]) ? $data[$field] : null;
    }
    
    public function getSessionData($sessionName){
        if(session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION[$sessionName])) {
            return null;
        }

        return $_SESSION[$sessionName];
    }

    public function sessionEmpty($sessionName){
        if(session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION[$sessionName])) {
            return true;
        }
        return false;
    }

    public function clearSession($sessionName){
        $_SESSION[$sessionName] = null;
    }
}