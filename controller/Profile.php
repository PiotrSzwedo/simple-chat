<?php 

class Profile extends Controller{

    public function default(){

        $userId = $this->sessionService->getSessionData("userId");

        if ($_SERVER['REQUEST_METHOD'] === "POST"){
            if (key_exists("action", $_POST)){

                if (!empty($_FILES) && $_POST["action"] === "changePhoto") {
                    $file = $this->fileService->uploadFileFromArray($_FILES);

                    if ($file){
                        $isPhotoChanges = $this->userService->changeProfilePhoto($file, $userId);
                    }
                }

                if ($_POST["action"] === "changeName" && key_exists("name", $_POST)){
                    $this->userService->changeName($_POST["name"], $userId);
                }
            }
        }

        $user = [];

        $user[0] = $this->userService->findById($userId);
        
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

        $this->generatePage($profile, [], ["profile"]);
    }
}