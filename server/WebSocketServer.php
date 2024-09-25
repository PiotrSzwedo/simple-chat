<?php

require_once __DIR__."/../vendor/cboden/ratchet/src/Ratchet/MessageInterface.php";
require_once __DIR__."/../vendor/cboden/ratchet/src/Ratchet/ConnectionInterface.php";

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class WebSocketServer implements MessageComponentInterface {
    protected $clients;
    protected $messageService;

    public function __construct($clients, $messageService) {
        $this->messageService = $messageService;
        $this->clients = $clients;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn, [
            'userId' => null,
            'active' => false
        ]);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if ($data["type"] == "registerId") {
            if ($this->clients->contains($from)) {
                $clientData = $this->clients[$from];
                $clientData["userId"] = $data["userId"];
                $clientData["active"] = true;
                $this->clients[$from] = $clientData;
            }
        }

        if (isset($data['userId']) && isset($data['message']) && $data["type"] == "message") {
            $data['message'] = htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8');
            $this->sendMessage($from, $data);
            $this->saveMessage($data['userId'], $data['message'], $data["sender"]);
        }
    }

    private function saveMessage($user, $message, $sender) {
        $this->messageService->send($sender, $user, $message);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    private function sendMessage($from, $data) {
        foreach ($this->clients as $client) {
            $clientData = $this->clients[$client];

            if (($clientData['userId'] == $data['userId'] ||
                $client === $from) &&
                $clientData["active"] === true) {
    
                $client->send(json_encode([
                    'type' => 'message',
                    'userId' => $data['userId'],
                    'message' => $data['message'],
                    'sender' => $data['sender'],
                ]));
    
                echo "Wiadomość wysłana do klienta: " . $clientData['userId'] . "\n";
            }
        }
    }
}