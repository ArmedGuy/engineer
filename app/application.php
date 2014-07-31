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


        $this->memcached = new Memcache();
        $this->memcached->connect("localhost", 11211);

        Scripts::register("bootstrap", [
          "app/public/js/jquery.min.js",
          "app/public/js/angular.min.js",
          "app/public/js/angular-resource.min.js",
          "app/public/js/angular-route.min.js",
          "app/public/js/bootstrap.min.js",
          "app/public/js/chartjs.min.js",
          "app/public/js/moment.min.js",
          "app/public/js/app.js"
        ]);

        Scripts::register("app", [
          "app/public/js/app/"
        ]);
    }
}
return new ptOS();