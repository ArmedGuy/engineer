<?php
namespace DbModel;
class MigrationTable {
  public $name;
  private $_columns;
  private $_keys;
  private $_storage;

  public function __construct($name, $storage = "InnoDB") {
    $this->name = $name;
    $this->_columns = [];
    $this->_keys = [];
    $this->_storage = $storage;
  }
  public function addColumn($type, $name, array $options) {
    $options["type"] = $type;
    $this->_columns[$name] = $options;
  }
  // string values
  public function string($name, array $options = []) {
    if(!isset($options["limit"]))
      $options["limit"] = 255;
    $this->addColumn("varchar", $name, $options);
  }
  public function text($name, array $options = []) {
    $this->addColumn("text", $name, $options);
  }
  // numbers
  public function integer($name, array $options = []) {
    if(!isset($options["limit"]))
      $options["limit"] = 11;
    $this->addColumn("int", $name, $options);
  }
  public function float($name, array $options = []) {
    $this->addColumn("float", $name, $options);
  }

  public function datetime($name, array $options = []) {
    $this->addColumn("datetime", $name, $options);
  }

  public function date($name, array $options = []) {
    $this->addColumn("date", $name, $options);
  }

  public function time($name, array $options = []) {
    $this->addColumn("time", $name, $options);
  }

  public function timestamp($name, array $options = []) {
    $this->addColumn("timestamp", $name, $options);
  }

  public function primary($name) {
    $this->_keys[$name] = "primary";
    $this->_columns[$name]["ai"] = true;
  }

  public function index($name) {
    $this->_keys[$name] = "index";
  }

  public function getSQL() {
    $sql = "CREATE TABLE `{$this->name}` (";
    foreach($this->_columns as $name=>$opt) {
      if(isset($opt["limit"]))
        $sql .= "`{$name}` {$opt['type']}({$opt['limit']})";
      else
        $sql .= "`{$name}` {$opt['type']}";

      if(isset($opt["null"]) && $opt["null"] == true) {
        $sql .= " NULL";
      } else {
        $sql .= " NOT NULL";
      }

      if(isset($opt["ai"]) && strpos($opt["type"], "int") !== false) {
        $sql .= " AUTO_INCREMENT";
      }

      if(isset($opt["default"])) {
        $sql .= " DEFAULT '{$opt['default']}";
      }
      $sql .= ",";
    }
    $keys = [];
    foreach($this->_keys as $key=>$type) {
      switch($type) {
        case "primary":
          $keys[] = "PRIMARY KEY (`{$key}`)";
          break;
        case "unique":
          $keys[] = "UNIQUE KEY (`{$key}`)";
          break;
        case "index":
          $keys[] = "INDEX (`{$key}`)";
          break;
      }
    }
    if(count($keys) > 0) {
      $sql .= implode(", ", $keys);
    } else {
      $sql = substr($sql, 0, strlen($sql)-1);
    }
    $sql .= ") ENGINE={$this->_storage}";
    return $sql;
  }
}