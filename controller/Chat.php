<?php

class Chat extends Controller
{
    public function default()
    {
        $this->conversation();
    }

    private function generateConversations($messages)
    {
        $data = [];

        foreach ($messages as $message) {
            $user1Id = $message["user1"];
            $recipientsId = $message["id"];

            $data[] = $this->generateMsg($user1Id, $recipientsId, $message["body"], $message["user1_send"]);
        }

        $element = (new HTMLElement("element", []));
        $element->addKid("element", new HTMLElement("element", ["element" => "<div class='con' style='display: block'>" . ($this->connectElements($data))->render() . "</div>"]));

        return $element;
    }

    private function generateMsg($user1Id, $recipientsId, $body, $user1SendMessage)
    {
        // user1 isn't recipients and send message
        if ($user1Id != $recipientsId && $user1SendMessage) {
            return new HTMLElement("mess", ["class" => "my", "body" => $body]);
        } // user1 is recipients and didn't send message
        if ($user1Id == $recipientsId && !$user1SendMessage) {
            return new HTMLElement("mess", ["class" => "my", "body" => $body]);
        } else {
            return new HTMLElement("mess", ["body" => $body]);
        }
    }

    private function generateUsersChats($recipients)
    {
        $users = [];

        // generating users list
        foreach ($recipients as $recipient) {
            if ($recipient[0]["id"]) {
                if ($recipient[0]["read_status"]) {
                    $users[] = new HTMLElement("conversation", ["name" => $recipient[0]["name"], "id" => $recipient[0]["id"], "class" => "not"]);
                } else {
                    $users[] = new HTMLElement("conversation", ["name" => $recipient[0]["name"], "id" => $recipient[0]["id"]]);
                }
            }
        }

        return $users;
    }

    public function conversation($id = null)
    {
        $userId = (new SessionService())->getSessionData("userId");

        if ($_POST && $_POST["message"] && $id) {

            $attachment = $_POST ?: 0;

            (new MessageService())->send($userId, $id, $_POST["message"], $attachment);
        }

        if (!$userId) {
            header("Location: /auth");
            return;
        }

        // downloading users from database
        $recipients = (new MessageService())->getConversations($userId);
        $recipients = (new ConvertService)->convertArrayByKey($recipients, "id");

        // creating array
        $conversations = [];
        $users = $this->generateUsersChats($recipients);

        // downloading chats from database 
        if ($id != null) {
            $messages = (new MessageService())->getAll($userId, $id);
            $conversations[] = $this->generateConversations($messages);
        }

        // generate page
        $chat = new HTMLElement("home", []);

        $chat->addKid("users", $this->connectElements($users));

        if ($id != null) {

            $chat->addKid("conversations", $this->connectElements($conversations));
            $sendingPanel = new HTMLElement("sendingPanel", []);

            $this->addTextToElement($sendingPanel, ["action" => $_SERVER["REQUEST_URI"]]);

            $chat->addKid("sendingPanel", $sendingPanel);

            $userName = (new UserService())->getName($id);
            $this->addTextToElement($chat, ["userName" => $userName, "userId" => $id]);
        } else {
            $this->addTextToElement($chat, ["style" => "display: none", "msgShow" => "show"]);
        }

        $this->generatePage($chat, [], ["mess"]);
    }
}