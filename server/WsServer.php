<?php

require_once __DIR__."/../vendor/cboden/ratchet/src/Ratchet/MessageInterface.php";
require_once __DIR__."/../vendor/cboden/ratchet/src/Ratchet/ConnectionInterface.php";

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class ChatServer implements MessageComponentInterface {
    protected $clients;
    protected $messageService;
    protected $sessionId;

    public function __construct() {
        $this->sessionId = (new SessionService())->getSessionData("userId");
        $this->messageService = new MessageService();
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients[$conn->resourceId] = [
            'connection' => $conn,
            'userId' => null,
            'active' => false
        ];
    }
    
    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
    
        if ($data["type"] === "registerId") {
            if (isset($this->clients[$from->resourceId])) {
                $this->clients[$from->resourceId]["userId"] = $data["userId"];
                $this->clients[$from->resourceId]["active"] = true;
            }
        }
    
        if (isset($data['userId']) && isset($data['message'])) {
            // Save message to database
            $this->saveMessage($data['userId'], $data['message']);
    
            foreach ($this->clients as $clientId => $clientData) {
                if ($clientData['userId'] == $data['userId'] &&
                    $clientData['connection'] !== $from &&
                    $clientData["active"] === true) {
                    $clientData['connection']->send(json_encode([
                        'type' => 'message',
                        'userId' => $data['userId'],
                        'message' => $data['message'],
                    ]));
                }
            }
        }
    }

    private function saveMessage($user, $message) {
        $this->messageService->send($this->sessionId, $user, $message);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}