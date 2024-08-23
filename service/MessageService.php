<?php 

class MessageService{

    private $db;

    public function __construct(){
        $this->db = new DatabaseService();
    }

    public function getAll($userId, $user2Id){
        $messages = $this->db->get("
        SELECT 
            chat.user.id,
            chat.message.user1,
            chat.message.user2,
            chat.conversation.body, 
            chat.conversation.attachment,
            chat.conversation.user1_send
        FROM 
            chat.conversation
        JOIN 
            chat.user RIGHT
        JOIN
            chat.message
            ON IF (chat.message.user1 = '$userId', chat.message.user2 = chat.user.id, chat.message.user1 = chat.user.id)
            AND chat.message.id = chat.conversation.mess_id
        WHERE
        chat.message.user1 = '$userId' AND chat.message.user2 = '$user2Id' OR chat.message.user2 = '$userId' AND chat.message.user1 = '$user2Id';
        ");

        return $messages ?: null;
    }

    public function getConversations($userId){
        $messages = $this->db->get("
        SELECT 
            chat.user.id,
            chat.user.name,
            chat.message.user1,
            chat.message.user2
        FROM 
            chat.user RIGHT
        JOIN
            chat.message
            ON IF (chat.message.user1 = '$userId', chat.message.user2 = chat.user.id, chat.message.user1 = chat.user.id)
        WHERE
        chat.message.user1 = '$userId' OR chat.message.user2 = '$userId';
        ");

        return $messages ?: null;
    }
}