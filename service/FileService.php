<?php 

class FileService{
    private $config;

    public function __construct($config){
        $this->config = $config;
    }

    public function uploadFileFromArray(array $file){
        
        if ($file["file"]["error"] = UPLOAD_ERR_OK && $file["file"]["size"] > $this->config["max_size"] && ! in_array($file["file"]["type"], $this->config["support_types"])){
            return false;
        }
        
        $pathinfo = pathinfo($file["file"]["name"]);
        
        $base = date("Hisu", microtime(true));
        
        $base = preg_replace("/[^\w-]/", "_", $base);
        
        $filename = $base . "." . $pathinfo["extension"];
        
        $destination = __DIR__ . "/../public/". $this->config["upload_dir"] . $filename;
        
        $i = 1;
        
        while (file_exists($destination)) {
        
            $filename = $base . "($i)." . $pathinfo["extension"];
            $destination = __DIR__ . "/../public/". $this->config["upload_dir"] . $filename;
        
            $i++;
        }
        
        if ( ! move_uploaded_file($file["file"]["tmp_name"], $destination)) {
            return false;
        }

        return true;
    }
}