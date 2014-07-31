<?php
class StaticMaps {
  private static $mapUrl = "http://maps.googleapis.com/maps/api/staticmap?center={coords}&zoom=7&size=600x300&scale=2&markers=color:red%7Clabel:A%7C{coords}";
  private static $memcached;

  public function __construct() {
    if(self::$memcached == null)
      self::$memcached = ptOS::$application->memcached;
  }
  public function fetch($key, $coords) {
    $c = $coords["lat"] . "," . $coords["lng"];
    $img = self::$memcached->get("staticmaps_".$key);
    if($img !== false) {
      return $img;
    } else {
      $img = file_get_contents(str_replace("{coords}", $c, self::$mapUrl));
      self::$memcached->set("staticmaps_".$key, $img, MEMCACHE_COMPRESSED);
      return $img;
    }
  }
}