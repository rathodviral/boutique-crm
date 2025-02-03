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
}
