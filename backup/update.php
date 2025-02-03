<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
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
$id = '';

if (isset($_GET['tablename'])) {
    $tablename = $_GET['tablename'];
} else {
    $common->errorHandling('table');
    return;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    $common->errorHandling('id');
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
    $update_val = $value;
    if ($key === 'detail') {
        $update_val = json_encode($value);
    }
    if ($key === 'isActive') {
        $update_val = $value ? 1 : 0;
    }
    $item = array("label" => $key, "value" => $update_val);
    array_push($list, $item);
}

if ($main->update($id, $list)) {
    $common->errorHandling('update_success', $tablename, true);
} else {
    $common->errorHandling('update_error', $tablename);
}
