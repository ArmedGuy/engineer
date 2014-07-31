<?php
use GeoIp2\Database\Reader;
class IpLookup {
  private static $memcached = null;
  private static $reader = null;
  public function __construct() {
    if(self::$memcached == null)
      self::$memcached = ptOS::$application->memcached;
    if(self::$reader == null)
      self::$reader = new Reader('./external/GeoLite2-City.mmdb');
  }

  public function lookup($ip) {
    $result = self::$memcached->get("iplookup_$ip");
    if($result === false) {
      $result = self::$reader->city($ip)->jsonSerialize();
      self::$memcached->set("iplookup_$ip", $result, false, 60*60*24);
    }
    return $result;
  }
}