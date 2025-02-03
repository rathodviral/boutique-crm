<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: multipart/form-data; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    return 0;
}
$path = "./images/";
$file_name  =  $_FILES['image']['name'];
$tempPath  =  $_FILES['image']['tmp_name'];
$file_size  =  $_FILES['image']['size'];

if (empty($file_name)) {
    die(json_encode(array("status" => false,  "message" => "No files")));
} else {
    $errors = array();
    $file_type = $_FILES['image']['type'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $tmp = explode('.', $_FILES['image']['name']);
    $file_ext = strtolower(end($tmp));

    $extensions = array("jpeg", "jpg", "png");

    $file = $path . $file_name;

    if (!in_array($file_ext, $extensions)) {
        $errors[] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
    }
    if ($file_size > 2097152) {
        $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
    }

    // http_response_code(200);
    if (empty($errors)) {
        move_uploaded_file($file_tmp, $file);
        die(json_encode(array("status" => true, "message" => "Yeah..", "imagePath" => "" . $file_name)));
    } else {
        die(json_encode(array("status" => false,  "message" => $errors)));
    }
}
