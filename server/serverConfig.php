<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/../server/WebSocketServer.php';
$db = require_once __DIR__.'/../config/db_conn_config.php';
$config = require __DIR__ . '/../config/ws_config.php';
$ssl = require __DIR__ . '/../config/ssl_config.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server as ReactSocket;


// Ustawienia serwera
$host = $config['host'];
$port = $config['port'];

// Opcjonalnie konfiguracja SSL
$sslContext = null;
if (!empty($ssl['enabled']) && $ssl['enabled']) {
    $sslContext = [
        'ssl' => [
            'local_cert' => $ssl['cert'],
            'local_pk' => $ssl['key'],
            'allow_self_signed' => true,
            'verify_peer' => false
        ]
    ];
}

// Utwórz serwer WebSocket
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketServer(new \SplObjectStorage, new MessageService(new DatabaseService(
                $db["address"], $db["user_name"], $db["user_password"], $db["db_name"], $db["port"]
            )))
        )
    ),
    $port,
    $host,
    $sslContext
);

// Uruchom serwer
$server->run();