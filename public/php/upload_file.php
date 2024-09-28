<?php

$config = require_once __DIR__."/../../config/file_upload_config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(500);
    exit("RFREQUEST_METHOD isn't post");
}

if (empty($_FILES)) {
    http_response_code(500);
    exit('FILES is empty');
}

if ($_FILES["file"]["error"] = UPLOAD_ERR_OK){
    http_response_code(500);
    exit("Upload error");
}

if ($_FILES["file"]["size"] > $config["max_size"]) {
    http_response_code(500);
    exit('File too large');
}

if (! in_array($_FILES["file"]["type"], $config["support_types"])){
    http_response_code(500);
    exit ("File type not support");
}

$pathinfo = pathinfo($_FILES["file"]["name"]);

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

if (move_uploaded_file($_FILES["file"]["tmp_name"], $destination)) {
    http_response_code(200);
}

echo $config["upload_dir"] . $filename;