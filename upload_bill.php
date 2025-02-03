<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: multipart/form-data; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    return 0;
}
$path = "./bills/";
$file_name  =  $_FILES['bill']['name'];
$tempPath  =  $_FILES['bill']['tmp_name'];
$file_size  =  $_FILES['bill']['size'];

if (empty($file_name)) {
    die(json_encode(array("status" => false,  "message" => "No files")));
} else {
    $errors = array();
    $file_type = $_FILES['bill']['type'];
    $file_tmp = $_FILES['bill']['tmp_name'];
    $tmp = explode('.', $_FILES['bill']['name']);
    $file_ext = strtolower(end($tmp));

    $extensions = array("pdf");

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
        die(json_encode(array("status" => true, "message" => "Yeah..", "billPath" => "" . $file_name)));
    } else {
        die(json_encode(array("status" => false,  "message" => $errors)));
    }
}
