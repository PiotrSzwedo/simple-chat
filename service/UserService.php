<?php

class UserService
{

    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }
    public function login($email, $password): bool
    {
        $user = $this->findByEmail($email);

        if ($user != null) {
            return password_verify($password, $user["password"]);
        }

        return false;
    }

    public function register($email, $name, $password): bool
    {
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

        $user = $this->findByEmail($email);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($user == null) {
            return $this->db->execute("INSERT INTO user (email, password, name) VALUES ('$email', '$hashedPassword', '$name')");
        }

        return false;
    }

    public function findByEmail($email): array
    {
        $user = $this->db->get("SELECT * FROM user where email = '$email'");

        if (key_exists(0 , $user)){
            return $user[0] ?: [];
        }

        return [];
    }

    public function getIdByEmail($email): ?int
    {
        $user = $this->findByEmail($email);


        if (!key_exists("id", $user)) return null;

        return $user["id"] ?: null;
    }

    public function findById($id): array
    {
        $user = $this->db->get("SELECT * FROM user where id = '$id'");

        if (key_exists(0 , $user)){
            return $user[0] ?: [];
        }

        return [];
    }

    public function getName($id)
    {
        $user = $this->findById($id);

        if ($user){
            $nameWithId = $user["name"];
        }

        return $nameWithId ?: "";
    }

    public function getPhoto($id){
        $user = $this->findById($id);

        if ($user){
            $nameWithId = $user["photo"];
        }

        return $nameWithId ?: [];
    }

    public function findByLetter($letter): ?array
    {
        $letter = htmlspecialchars($letter, ENT_QUOTES, 'UTF-8');
        $users = $this->db->get("SELECT *
            FROM user
            WHERE name LIKE '%$letter%'
            ORDER BY 
                CASE 
                    WHEN LOCATE('$letter', name) = 2 THEN 0  
                    WHEN LOCATE('$letter', name) = 1 THEN 1 
                    ELSE 2                       
                END,
                name;
        ");

        return $users ?: [];
    }
}