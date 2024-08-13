<?php 

class AuthService{

    private $db;

    public function __construct(){
        $this->db = new DatabaseService();
    }
    public function login($email, $password){
        $user = $this->findByEmail($email);

        if ($user != null){
            return password_verify($password, $user["password"]);
        }

        return false;
    }

    public function register($email, $name, $password){
        var_dump($name);
        $user = $this->findByEmail($email);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($user == null){
            $this->db->execute("INSERT INTO user (email, password, name) VALUES ('$email', '$hashedPassword', '$name')");
            return true;
        }

        return false;
    }

    public function findByEmail($email){
        $user = $this->db->get("SELECT * FROM user where email = '$email'");

        if ($user){
            return $user[0];
        }else{
            return null;
        }
    }
}