<?php 

class Search extends Controller{

    private UserService $userService;

    public function __construct($action, $parameters){
        $this->userService = new UserService();

        parent::__construct($action, $parameters);
    }


    public function default(){
        $search = new HTMLElement("search", []);

        if (key_exists("letter", $_POST) && !empty($_POST["letter"])){
            $users = $this->userService->findByLetter($_POST["letter"]);

            if (is_array($users)){
                $usersElement = new HTMLMultiElement("searchElement", $users);

                $search->addKid("result", $usersElement);
            }

            $this->addTextToElement($search, ["value" => $_POST["letter"]]);
        }

        echo $this->generatePage($search);
    }
}