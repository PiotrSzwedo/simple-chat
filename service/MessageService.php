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
                u.id,
                u.name,
                m.user1,
                m.user2,
                m.last_msg
            FROM 
                chat.message m
            RIGHT JOIN
                chat.user u
                ON (m.user1 = '$userId' AND m.user2 = u.id) 
                OR (m.user2 = '$userId' AND m.user1 = u.id)
            WHERE
                m.user1 = '$userId' OR m.user2 = '$userId'
            ORDER BY 
                m.last_msg DESC;
        ");

        return $messages ?: null;
    }

    public function send($senderId, $recipientId, $message, $attachment = 0){
        $data = date("yy-m-d H:i:s");

        $this->db->execute("
            UPDATE `message` 
            SET `last_msg` = '$data'
            WHERE 
            user1 = '$senderId' AND user2 = '$recipientId' OR 
            user2 = '$senderId' AND user1 = '$recipientId';
        ");

        $this->db->execute("
        INSERT INTO `chat`.`conversation` 
        (`mess_id`, `body`, `attachment`, `user1_send`) 
        VALUES (
            (SELECT id FROM chat.message 
            WHERE (user1 = '$senderId' AND user2 = '$recipientId') 
                OR (user2 = '$senderId' AND user1 = '$recipientId')
            LIMIT 1),
            '$message', 
            '$attachment', 
            (IF((SELECT user1 FROM chat.message WHERE id = (SELECT id FROM chat.message WHERE (user1 = '$senderId' AND user2 = '$recipientId') OR (user2 = '$senderId' AND user1 = '$recipientId'))) = '$senderId', 1, 0))
        );
        ");
    }
}