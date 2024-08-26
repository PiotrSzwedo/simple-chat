<?php 

class DatabaseService{

    private mysqli $db;
    
    public function __construct()
    {
        $this->db = new mysqli("localhost","root","","chat");
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