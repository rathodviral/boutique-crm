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
    );
  }

  public function errorHandling($data, $info = null, $status = false)
  {
    http_response_code(200);
    die(json_encode(
      array("status" => $status, "message" => isset($info) ? $info . ' : ' . $this->errors[$data] : $this->errors[$data])
    ));
  }
}
