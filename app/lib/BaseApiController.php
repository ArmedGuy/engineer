<?php
class BaseApiController extends \Munition\AppController {
  protected static $memcached = null;
  function __construct() {
    self::$memcached = ptOS::$application->memcached;
  }

  function cache_request($ctx) {
    if($_SERVER["REQUEST_METHOD"] != "GET")
      return $ctx;

    $key = md5($_SERVER['REQUEST_URI']);
    $data = self::$memcached->get("apicache_$key");
    if($data === false) {
      return $ctx;
    } else {
      self::render(["json" => $data]);
    }
  }

  public static function render($ctx, $data = null) {
    if($data == null && $_SERVER["REQUEST_METHOD"] == "GET") {
      $data = $ctx;
    } else {
      return parent::render($ctx, $data);
    }
    if(isset($data["json"])) {
      $key = md5($_SERVER['REQUEST_URI']);
      self::$memcached->set("apicache_$key", $data["json"], MEMCACHE_COMPRESSED, 60);
    }
    parent::render($data);
  }

}