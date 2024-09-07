<?php 

class DatabaseService{

    private mysqli $db;
    
    public function __construct($address, $userName, $password, $dbName, $port = 3306)
    {
        $this->db = new mysqli($address, $userName, $password, $dbName, $port);
    }

    public static function createByConfig($config){
        
    }

    public function get($sql){
        $result = $this->db->query($sql);

        if (!empty($result)) {

            $rows = [];

            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            return $rows;
        }
    }

    public function execute($sql){
        $result = $this->db->query($sql);

        if ($result)
            return true;
        
        return false;
    }
}