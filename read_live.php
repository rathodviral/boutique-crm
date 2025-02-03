<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
$list = $common->map_Search_payload_to_table_data($data);
$stmt = $main->read_live($list);
$count = $stmt->rowCount();

if ($count > 0) {
    $exp = array();
    $exp["status"] = true;
    $exp["data"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $p  = $common->map_table_data_to_object($row);
        array_push($exp["data"], $p);
    }
    http_response_code(200);
    die(json_encode($exp));
} else {
    $common->errorHandling('read_error');
}
