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

$dbclass = new DBClass();
$connection = $dbclass->getConnection();

$category = new Main($connection, 'category');
$sub_category = new Main($connection, 'sub_category');
$sub_sub_category = new Main($connection, 'sub_sub_category');
// $stock = new Main($connection, 'stock');
// $customer = new Main($connection, 'customer');

$stmt_category = $category->read();
$stmt_sub_category = $sub_category->read();
$stmt_sub_sub_category = $sub_sub_category->read();
// $stmt_stock = $stock->read();
// $stmt_customer = $customer->read();

$count_category = $stmt_category->rowCount();
$count_sub_category = $stmt_sub_category->rowCount();
$count_sub_sub_category = $stmt_sub_sub_category->rowCount();
// $count_stock = $stmt_stock->rowCount();
// $count_customer = $stmt_customer->rowCount();

if ($count_category > 0 || $count_sub_category > 0 || $count_sub_sub_category > 0 || $count_stock > 0 || $count_customer > 0) {
    $exp = array();
    $exp["status"] = true;
    $exp["category"] = array();
    $exp["subCategory"] = array();
    $exp["subSubCategory"] = array();
    // $exp["stock"] = array();
    // $exp["customer"] = array();

    while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
        $p  = $common->map_table_data_to_object($row);
        array_push($exp["category"], $p);
    }

    while ($row = $stmt_sub_category->fetch(PDO::FETCH_ASSOC)) {
        $p  = $common->map_table_data_to_object($row);
        array_push($exp["subCategory"], $p);
    }

    while ($row = $stmt_sub_sub_category->fetch(PDO::FETCH_ASSOC)) {
        $p  = $common->map_table_data_to_object($row);
        array_push($exp["subSubCategory"], $p);
    }

    // while ($row = $stmt_stock->fetch(PDO::FETCH_ASSOC)) {
    //     $p  = $common->map_table_data_to_object($row);
    //     array_push($exp["stock"], $p);
    // }

    // while ($row = $stmt_customer->fetch(PDO::FETCH_ASSOC)) {
    //     $p  = $common->map_table_data_to_object($row);
    //     array_push($exp["customer"], $p);
    // }

    http_response_code(200);
    die(json_encode($exp));
} else {
    $common->errorHandling('read_error');
}
