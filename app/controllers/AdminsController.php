<?php
class AdminsController extends BaseApiController {
  function __construct() {
    parent::__construct();
    $this->beforeAction("static::cache_request", ["not" => []]);
  }
  function index() {
      $admins = array_map(function($s) {
          return $s->toArray();
      }, Admin::all()->getArrayCopy());
      self::render(["json" => $admins]);
  }

  function show($ctx, $params) {
      $id = $params["server_id"];
      $admin = Server::find($id)->obj();
      if($admin != null) {
          self::render(["json" => $admin->serialize()]);
      }
  }

  function create($ctx, $params) {
    $username = $params["username"];
    $password = password_hash($params["password"], PASSWORD_BCRYPT);
    $type = $params["type"];

    Admin::create(["username" => $username, "password" => $password, "type" => $type]);
    self::render(["json" => Admin::get()->last]);
  }

  function edit($ctx, $params) {
    $id = $params["admin_id"];
    $username = $params["username"];
    $password = password_hash($params["password"], PASSWORD_BCRYPT);
    $type = $params["type"];

    $admin = Admin::find($id)->obj();
    if($admin != null) {
      $admin->username = $username;
      $admin->password = $password;
      $admin->type = $type;
    } else {
      self::render([422, "json" => ["error" => true]]);
    }
  }

  function delete($ctx, $params) {
    $id = $params["admin_id"];
    $admin = Admin::find($id)->obj();
    if($admin != null) {
      $admin->destroy();
      self::render(["json" => ["result" => "OK"]]);
    } else {
      self::render([422, "json" => ["error" => true]]);
    }
  }
}