<?php 

class UserService{

    private $db;

    public function __construct(){
        $this->db = new DatabaseService();
    }
    public function login($email, $password) :bool{
        $user = $this->findByEmail($email);

        if ($user != null){
            return password_verify($password, $user["password"]);
        }

        return false;
    }

    public function register($email, $name, $password) :bool{
        var_dump($name);
        $user = $this->findByEmail($email);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($user == null){
            return $this->db->execute("INSERT INTO user (email, password, name) VALUES ('$email', '$hashedPassword', '$name')");
        }

        return false;
    }

    public function findByEmail($email): ?array{
        $user = $this->db->get("SELECT * FROM user where email = '$email'");

        return $user[0] ?: null;
    }

    public function getIdByEmail($email): ?int{
        $user = $this->findByEmail($email);
            
        return $user["id"] ?: null;
    }

    public function findById($id): ?array{
        $user = $this->db->get("SELECT * FROM user where id = '$id'");

        return $user[0] ?: null;
    }

    public function getName($id){
        $user = $this->findById($id);

        $nameWithId = $user["name"];

        return $nameWithId ?: null;
    }
}