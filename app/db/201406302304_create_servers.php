<?php
class CreateServers extends \DbModel\Migration {
    public function up() {
        $this->createTable("servers", function($t) {
            $t->integer("id");
            $t->primary("id");

            $t->string("guid");

            $t->string("name");
            $t->string("path");

            $t->string("auth_key");
        });


        Server::create(["guid" => "bf2_eu1", "name" => "NRNS-GAMES.COM! 247 Karkand", "path"=> "localhost:80", "auth_key" => "test"]);

    }

    public function down() {
        $this->deleteTable("servers");
    }
}