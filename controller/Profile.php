<?php 

class Profile extends Controller{
    private $userId;
    private $userService;

    public function __construct($action, $parameters){
        $this->userService = new UserService();
        $this->userId = (new SessionService())->getSessionData("userId");

        parent::__construct($action, $parameters);
    }

    public function default(){
        $user = [];

        $user[0] = $this->userService->findById($this->userId);
        
        $profileHome = new HTMLMultiElement("profileHome", $user);

        $this->generateProfilePage($profileHome);
    }

    public function security(){
        $this->generateProfilePage(null);
    }

    public function language(){
        $this->generateProfilePage(null);
    }

    private function generateProfilePage($data){
        $profile = new HTMLElement("profile", []);

        if ($data && $data != null){
            $profile->addKid("page", $data);
        }

        $this->addTextToElement($profile, ["profile" => $this->description["profile-profile"], "security" => $this->description["profile-security"], "languages" => $this->description["profile-languages"], "back" => $this->description["profile-back"]]);

        $this->generatePage($profile);
    }
}