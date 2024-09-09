<?php

require_once __DIR__.'/../service/MessageService.php';
require_once __DIR__.'/../service/DatabaseService.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/WebSocketServer.php';
$db = require_once __DIR__.'/../config/db_conn_config.php';
$config = require __DIR__ . '/../config/ws_config.php';
$ssl = require __DIR__ . '/../config/ssl_config.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server as ReactSocket;

Factory::create();


$host = $config['host'];
$port = $config['port'];

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketServer(new \SplObjectStorage, new MessageService(new DatabaseService(
                $db["address"], $db["user_name"], $db["user_password"], $db["db_name"], $db["port"]
            )))
        )
    ),
    $port,
    $host
);

$server->run();