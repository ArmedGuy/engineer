<?php
class Engineer extends \Munition\App {
    public function __construct() {
        parent::__construct("./app/");

        $r = $this->router;
        include 'routes.php';

        $this->db = new \DbModel\AppDbManager();
        $this->db->using("main" , [
            "user" => "root",
            "db" => "engineer"
        ]);
        \DbModel\Base::$default_db = $this->db->main;

        foreach(array_diff(scandir("./app/db/"), ["..", "."]) as $file) {
            include 'db/' . $file;
            $file = str_replace(".php","", $file);

            $v = new $file($this->db->main);
            $v->down();
            $v->up();
        }
    }
}
return new Engineer();