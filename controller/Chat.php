<?php

class Chat extends Controller
{

    protected MessageService $messageService;

    protected UserService $userService;

    public function __construct($action, $parameters){
        $this->userService = new UserService();
        $this->messageService = new MessageService();

        parent::__construct($action, $parameters);
    }

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
                if (!$recipient[0]["read_status"]) {
                    $users[] = new HTMLElement("conversation", ["name" => $recipient[0]["name"], "id" => $recipient[0]["id"], "class" => "not", "photo" => $recipient[0]["photo"]]);
                } else {
                    $users[] = new HTMLElement("conversation", ["name" => $recipient[0]["name"], "id" => $recipient[0]["id"], "photo" => $recipient[0]["photo"]]);
                }
            }
        }

        return $users;
    }

    public function conversation($id = null)
    {
        $chat = new HTMLElement("home", []);
        $userId = (new SessionService())->getSessionData("userId");
    
        if (!$userId) {
            header("Location: /auth");
            return;
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["message"]) && !empty($_POST["message"]) && $id) {
            $this->messageService->send($userId, $id, $_POST["message"]);
        }
    
        $recipients = $this->messageService->getConversations($userId);

        if (is_array($recipients) && key_exists("id", $recipients[0])){
            
            $recipients = (new ConvertService())->convertArrayByKey($recipients, "id");

            $conversations = [];
            $users = $this->generateUsersChats($recipients);
        
            $chat->addKid("users", $this->connectElements($users));
        
            if ($id != null) {
                $this->messageService->refreshReadStatus($userId, $id);

                $messages = $this->messageService->getAll($userId, $id);

                if ($messages) {
                    $conversations[] = $this->generateConversations($messages);
                    $chat->addKid("conversations", $this->connectElements($conversations));
                }
            } 
        }

        $sendingPanel = new HTMLElement("sendingPanel", []);
        $this->addTextToElement($sendingPanel, ["action" => $_SERVER["REQUEST_URI"]]);
        
        if ($id != null){
            $userName = $this->userService->getName($id);
            $this->addTextToElement($chat, ["userName" => $userName, "userId" => $id]);
            $chat->addKid("sendingPanel", $sendingPanel);
        }else{
            $this->addTextToElement($chat, ["style" => "display: none", "msgShow" => "show"]);
        }
        
        $this->generatePage($chat, [], ["mess", "reload"]);
    }
}