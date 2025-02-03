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
    $item = array("create_label" => $key, "create_value" => $key === 'details' ? json_encode($value) : $value);
    array_push($list, $item);
}

if ($main->create($list)) {
    $stmt = $main->read_last();
    $count = $stmt->rowCount();
    if ($count > 0) {
        $exp = array();
        $exp["status"] = true;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $exp["id"] = $id;
            $exp["details"] = json_decode($details);
            $exp["label"] = $label;
            if ($tablename === 'sub_category') {
                $exp["categoryId"] = $category_id;
            }
        }
        http_response_code(200);
        die(json_encode($exp));
    } else {
        $common->errorHandling('create_read_error', $tablename, true);
    }
} else {
    $common->errorHandling('create_error', $tablename);
}
