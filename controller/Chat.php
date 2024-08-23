<?php 

class Chat extends Controller{
    public function default(){
        $userId = (new SessionService())->getSessionData("userId");

        if (!$userId){
            header("Location: /auth");
            return;
        }

        $chat = new HTMLElement("home", []);

        
        $messages = (new MessageService())->getConversations($userId);
        $messages = (new ConvertService)->convertArrayByKey($messages, "id");
        $users = [];

        foreach ($messages as $message){
            if ($message[0]["id"]){
                $users[] = new HTMLElement("conversation", ["name" => $message[0]["name"], "id" => $message[0]["id"]]);
            }
        }
        
        $chat->addKid("users", $this->connectElements($users));
        $this->generatePage($chat, [], ["mess"]);
    }

    private function generateConversations($messages){
        $data = [];

        foreach ($messages as $message){
                $user1Id = $message["user1"];
                $recipientsId = $message["id"];

                $data[] = $this->generateMess($user1Id, $recipientsId, $message["body"], $message["user1_send"]);
        }

        $element = (new HTMLElement("element", []));
        $element->addKid("element", new HTMLElement("element", ["element" => "<div class='con' style='display: block'>".($this->connectElements($data))->render()."</div>"]));

        return $element;
    }

    private function generateMess($user1Id, $recipientsId, $body, $user1SendMessage){
        // user1 isn't recipients and send message
        if ($user1Id != $recipientsId && $user1SendMessage){
            return new HTMLElement("mess", ["class" => "my", "body" => $body]);
        } // user1 is recipients and didn't send message
        if($user1Id == $recipientsId && !$user1SendMessage){
            return new HTMLElement("mess", ["class" => "my", "body" => $body]);
        }else {
            return new HTMLElement("mess", ["body" => $body]);
        }
    }

    public function conversation($id = null){
        $userId = (new SessionService())->getSessionData("userId");

        if (!$userId){
            header("Location: /auth");
            return;
        }

        
        // creating array
        $users = [];
        $conversations = [];
        
        // downloading users from database
        $recipients = (new MessageService())->getConversations($userId);
        $recipients = (new ConvertService)->convertArrayByKey($recipients, "id");
        
        // downloading chats from database 
        if ($id != null){
            $messages = (new MessageService())->getAll($userId, $id);
            $conversations[] = $this->generateConversations($messages);
        }
        
        
        // generating users list
        foreach ($recipients as $recipient){
            if ($recipient[0]["id"]){
                $users[] = new HTMLElement("conversation", ["name" => $recipient[0]["name"], "id" => $recipient[0]["id"]]);
            }
        }
        
        // generate page
        $chat = new HTMLElement("home", []);
        
        $chat->addKid("users", $this->connectElements($users));
        
        if ($id != null){
            $chat->addKid("conversations", $this->connectElements($conversations));
        }

        $this->generatePage($chat, [], ["mess"]);
    }
}