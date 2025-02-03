<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->username) || !isset($data->password)) {
    $common->errorHandling('data', $tablename);
    return;
}

$dbclass = new DBClass();
$connection = $dbclass->getConnection();

$main = new Main($connection, $tablename);

$tokenData = time() . "_" . $data->username . "-" . $data->password;
$token = $common->createToken($tokenData);

$stmt = $main->authorize($data);
$count = $stmt->rowCount();

if ($count > 0) {
    $user = array();
    $user["status"] = true;
    $user["token"] = $token;
    $user["message"] = "Success";
    $user["data"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $p  = array(
            "username" => $username,
            "isAdmin" => $isAdmin === '1',
            "detail" => json_decode($detail),
        );
        $user["data"] = $p;
    }
    http_response_code(200);
    die(json_encode($user));
} else {
    $common->errorHandling('auth_error', $tablename);
}
