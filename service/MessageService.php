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

    public function getConversations($userId) :?array{
        $messages = $this->db->get("
            SELECT 
                u.id,
                u.name,
                m.user1,
                m.user2,
                m.last_msg,
                c.read_status
            FROM 
                chat.user u
            JOIN 
                chat.message m
                ON (m.user1 = '$userId' AND m.user2 = u.id) 
                OR (m.user2 = '$userId' AND m.user1 = u.id)
            JOIN 
                (
                    SELECT 
                        c.mess_id,
                        IF(me.user1 = '$userId',
                            MIN(c.user1_read),
                            MIN(c.user2_read)
                        ) AS read_status
                    FROM 
                        chat.conversation c
                    JOIN 
                        chat.message me
                        ON c.mess_id = me.id
                    WHERE 
                        me.user1 = '$userId' OR me.user2 = '$userId'
                    GROUP BY 
                        c.mess_id
                ) AS c
                ON c.mess_id = m.id
            ORDER BY 
                m.last_msg DESC;
        ");

        return $messages ?: null;
    }

    public function send($senderId, $recipientId, $message, $attachment = 0) :void{
        $data = date("yy-m-d H:i:s");

        if (!$this->conversationExist($senderId, $recipientId)){
            if (!$this->createConversation($senderId, $recipientId))
                return;
        }


        $this->db->execute("
            UPDATE `message` 
            SET `last_msg` = '$data'
            WHERE 
            user1 = '$senderId' AND user2 = '$recipientId' OR 
            user2 = '$senderId' AND user1 = '$recipientId';
        ");

        $this->db->execute("
            INSERT INTO `chat`.`conversation` 
            (`mess_id`, `body`, `attachment`, `user1_send`, `user1_read`, `user2_read`) 
            VALUES (
                (
                    SELECT id 
                    FROM chat.message 
                    WHERE (user1 = '$senderId' AND user2 = '$recipientId') 
                    OR (user2 = '$senderId' AND user1 = '$recipientId')
                    LIMIT 1
                ),
                '$message', 
                '$attachment', 
                (IF (
                    (SELECT user1 FROM chat.message 
                    WHERE id = (
                        SELECT id 
                        FROM chat.message 
                        WHERE (user1 = '$senderId' AND user2 = '$recipientId') 
                            OR (user2 = '$senderId' AND user1 = '$recipientId')
                        LIMIT 1)
                    ) = '$senderId', 1, 0)
                ),
                (IF (
                    (SELECT user1 FROM chat.message 
                    WHERE id = (
                        SELECT id 
                        FROM chat.message 
                        WHERE (user1 = '$senderId' AND user2 = '$recipientId') 
                            OR (user2 = '$senderId' AND user1 = '$recipientId')
                        LIMIT 1)
                    ) = '$senderId', 1, 0)
                ),
                (IF (
                    (SELECT user2 FROM chat.message 
                    WHERE id = (
                        SELECT id 
                        FROM chat.message 
                        WHERE (user1 = '$senderId' AND user2 = '$recipientId') 
                            OR (user2 = '$senderId' AND user1 = '$recipientId')
                        LIMIT 1)
                    ) = '$senderId', 1, 0)
                )
            );
        ");
    }

    public function conversationExist($user1, $user2) :bool{
        $conversation = $this->db->get("          
        SELECT * 
        from message
        WHERE 
        user1 = '$user1' AND user2 = '$user2' OR 
        user2 = '$user1' AND user1 = '$user2';
        ");

        if ($conversation){
            return true;
        }
        
        return false;
    }

    public function createConversation($user1, $user2){
        return $this->db->execute("INSERT INTO `chat`.`message` (`user1`, `user2`) VALUES ('$user1', '$user2');");
    }
}