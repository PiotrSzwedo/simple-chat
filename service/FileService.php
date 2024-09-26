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
        
        $base = preg_replace("/[^\w-]/", "_", date("Hisu", microtime(true)));

        return $this->moveFile($file["file"]["tmp_name"], $pathinfo["extension"], $base);
    }

    private function moveFile(string $tmpName, string $extension, string $fileName){ 
        $fileName = $fileName . "." . $extension;

        $destination = __DIR__ . "/../public/". $this->config["upload_dir"] . $fileName;
        
        $i = 1;
        
        while (file_exists($destination)) {
        
            $fileName = $fileName . "($i)." . $extension;
            $destination = __DIR__ . "/../public/". $this->config["upload_dir"] . $fileName;
        
            $i++;
        }
        
        if ( ! move_uploaded_file($tmpName, $destination)) {
            return;
        }

        return $this->config["upload_dir"] . $fileName;;
    }

    public function deleteFileWithFullPath($fileName){
        unlink($fileName);
    }
}