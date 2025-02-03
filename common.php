<?php
class Common
{
  private $errors;

  public function __construct()
  {

    $this->errors = array(
      'table' => 'No table available',
      'id' => 'No id available.',
      'data' => 'No data available.',
      'create_success' => 'create : Yeah..',
      'create_error' => 'create : Opps..',
      'create_read_error' => 'created : not read : Opps..',
      'update_success' => 'update : Yeah..',
      'update_error' => 'update : Opps..',
      'delete_success' => 'delete : Yeah..',
      'delete_error' => 'delete : Opps..',
      'read_success' => 'read : Yeah..',
      'read_error' => 'read : Opps..',
      'auth_error' => 'Auth : Opps..',
    );
  }

  public function errorHandling($data, $info = null, $status = false)
  {
    http_response_code(200);
    die(json_encode(
      array("status" => $status, "message" => isset($info) ? $info . ' : ' . $this->errors[$data] : $this->errors[$data])
    ));
  }

  public function createToken($data)
  {
    define('SECRET_KEY', "crm");
    $tokenGeneric = SECRET_KEY . $_SERVER["SERVER_NAME"]; // It can be 'stronger' of course
    /* Encoding token */
    $token = hash('sha256', $tokenGeneric . $data);
    // return array('token' => $token, 'userData' => $data);
    return $token;
  }

  public function map_table_data_to_object($row)
  {
    extract($row);
    $p  = array();
    if (isset($id)) {
      $p["id"] = intval($id);
    }
    if (isset($label)) {
      $p["label"] = $label;
    }
    if (isset($details)) {
      $p["details"] = json_decode($details);
    }
    if (isset($categoryId)) {
      $p["categoryId"] = intval($categoryId);
    }
    if (isset($subCategoryId)) {
      $p["subCategoryId"] = intval($subCategoryId);
    }
    if (isset($category)) {
      $p["category"] = json_decode($category);
    }
    if (isset($tags)) {
      $p["tags"] = json_decode($tags);
    }
    if (isset($items)) {
      $p["items"] = json_decode($items);
    }
    if (isset($customer)) {
      $p["customer"] = json_decode($customer);
    }
    if (isset($image)) {
      $p["image"] = $image;
    }
    if (isset($quantity)) {
      $p["quantity"] = intval($quantity);
    }
    if (isset($price)) {
      $p["price"] = intval($price);
    }
    if (isset($cost)) {
      $p["cost"] = intval($cost);
    }
    if (isset($description)) {
      $p["description"] = $description;
    }
    if (isset($address)) {
      $p["address"] = $address;
    }
    if (isset($email)) {
      $p["email"] = $email;
    }
    if (isset($contact)) {
      $p["contact"] = $contact;
    }
    if (isset($name)) {
      $p["name"] = $name;
    }
    if (isset($billNo)) {
      $p["billNo"] = $billNo;
    }
    if (isset($date)) {
      $p["date"] = $date;
    }
    if (isset($stock)) {
      $p["stock"] = intval($stock);
    }
    if (isset($total)) {
      $p["total"] = intval($total);
    }
    return $p;
  }

  public function read_data_from_table($main)
  {
    $stmt = $main->read();
    $count = $stmt->rowCount();
    if ($count > 0) {
      $exp = array();
      $exp["status"] = true;
      $exp["data"] = array();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $p  = $this->map_table_data_to_object($row);
        array_push($exp["data"], $p);
      }
      http_response_code(200);
      die(json_encode($exp));
    } else {
      $this->errorHandling('create_read_error', $main->tablename, true);
    }
  }

  public function map_payload_to_table_data($data)
  {
    $list = array();
    foreach ($data as $key => $value) {
      $item = array("create_label" => $key, "create_value" => $key === 'details' || $key === 'items' || $key === 'customer' || $key === 'tags' || $key === 'category' ? json_encode($value) : $value);
      array_push($list, $item);
    }
    return $list;
  }

  public function map_Search_payload_to_table_data($data)
  {
    $list = array();
    foreach ($data as $key => $value) {
      $item = array("create_label" => $key, "create_value" => $value);
      array_push($list, $item);
    }
    return $list;
  }
}
