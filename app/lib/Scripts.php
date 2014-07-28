<?php
class Scripts {
  private static $scriptBundles = [];
  public static function register($name, $scripts) {
    foreach($scripts as $script) {
      if(!is_file($script)) {
        if(is_dir($script)) {
          self::addScriptsInDirectory($name, $script);
        }
      } else {
        self::$scriptBundles[$name][] = $script;
      }
    }
  }

  public static function render($name) {
    if(isset(self::$scriptBundles[$name])) {
      foreach(self::$scriptBundles[$name] as $script) {
        echo "<script type=\"text/javascript\" src=\"$script\"></script>";
      }
    }
  }

  private static function addScriptsInDirectory($name, $directory) {
    $cdir = array_diff(scandir($directory), ["..", "."]);
    foreach($cdir as $file) {
      if(is_dir($directory . DIRECTORY_SEPARATOR . $file)) {
        self::addScriptsInDirectory($name, $directory . "/" . $file);
      } else {
        if(strpos($file, ".js") !== false) {
          self::$scriptBundles[$name][] = $directory . "/" . $file;
        }
      }
    }
  }
}