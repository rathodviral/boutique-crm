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

$data = json_decode(file_get_contents("php://input"));
if (!$data) {
    $common->errorHandling('data', 'bill');
    return;
}

$dbclass = new DBClass();
$connection = $dbclass->getConnection();

$main = new Main($connection, 'bill');
$list = $common->map_payload_to_table_data($data);

$stmt = $main->create_with_return($list);
if ($stmt->execute() && isset($data->items)) {
    $list_stock = array();
    for ($i = 0; $i < count($data->items); $i++) {
        $item = $data->items[$i];
        array_push($list_stock, $item);
    };
    if ($main->update_multi($list_stock)) {
        $common->read_data_from_table($main);
    } else {
        $common->errorHandling('update_error', $stmt->errorInfo()[2]);
    }
} else {
    $common->errorHandling('create_error', $stmt->errorInfo()[2]);
}
