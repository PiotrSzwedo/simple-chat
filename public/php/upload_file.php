<?php

$config = require_once __DIR__."/../../config/file_upload_config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("RFREQUEST_METHOD isn't post");
}

var_dump($fileConf);

if (empty($fileConf)) {
    exit('FILES is empty');
}

if ($fileConf["file"]["error"] = UPLOAD_ERR_OK){
    exit("Upload error");
}

if ($fileConf["file"]["size"] > $config["max_size"]) {
    exit('File too large');
}

if (! in_array($fileConf["file"]["type"], $config["support_types"])){
    exit ("File type not support");
}

$pathinfo = pathinfo($fileConf["file"]["name"]);

$base = date("Hisu", microtime(true));

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$destination = __DIR__ . "/..". $config["upload_dir"] . $filename;

$i = 1;

while (file_exists($destination)) {

    $filename = $base . "($i)." . $pathinfo["extension"];
    $destination = __DIR__ . "/..". $config["upload_dir"] . $filename;

    $i++;
}

if ( ! move_uploaded_file($fileConf["file"]["tmp_name"], $destination)) {
    exit("Can't move uploaded file");
}

http_response_code(200);
echo $destination;