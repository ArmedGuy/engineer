<?php
class ptOS extends \Munition\App {
    public function setup() {
        parent::configure("./app/");

        $r = $this->router;
        include 'routes.php';

        $this->db = new \DbModel\AppDbManager();
        $this->db->using("main" , [
            "user" => "root",
            "db" => "ptos"
        ]);

        \DbModel\Base::$default_db = $this->db->main;
        \DbModel\Migration::register_cli_hooks($this->cli);
    }
}
return new ptOS();