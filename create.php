<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    return 0;
}

include_once 'database.php';
include_once 'common.php';
include_once 'main.php';

$common = new Common();

$tablename = '';

if (isset($_GET['tablename'])) {
    $tablename = $_GET['tablename'];
} else {
    $common->errorHandling('table');
    return;
}

$data = json_decode(file_get_contents("php://input"));
if (!$data) {
    $common->errorHandling('data', $tablename);
    return;
}

$dbclass = new DBClass();
$connection = $dbclass->getConnection();

$main = new Main($connection, $tablename);

$list = array();

foreach ($data as $key => $value) {
    $item = array("label" => $key, "value" => $key === 'detail' ? json_encode($value) : $value);
    array_push($list, $item);
}

if ($main->create($list)) {
    $common->errorHandling('create_success', $tablename, true);
} else {
    $common->errorHandling('create_error', $tablename);
}
