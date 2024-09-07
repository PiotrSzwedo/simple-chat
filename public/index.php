<?php

    $db = require_once __DIR__.'/../config/db_conn_config.php';
    function loader($className) 
    {
        $dirs = [
            '/../controller/',
            '/../generator/',
            '/../interfaces/',
            '/../router/',
            '/../service/'
        ];
    
        foreach ($dirs as $dir) {
            $filePath = __DIR__ . $dir . $className . '.php';
            if (is_file($filePath)) {
                include_once $filePath;
            }
        }
    }

spl_autoload_register('loader');

$router = new Router(new DatabaseService(
    $db["address"], $db["user_name"], $db["user_password"], $db["db_name"], $db["port"]
));