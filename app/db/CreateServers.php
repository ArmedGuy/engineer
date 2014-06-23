<?php
class CreateServers extends \DbModel\Migration {
    public function up() {
        $this->createTable("servers", function($t) {
            $t->integer("id");
            $t->primary("id");

            $t->string("game_ident");
            $t->string("ident");

            $t->string("name");
            $t->string("ip");
            $t->string("port");

            $t->string("auth_key");
        });


        Server::create(["game_ident" => "bf2", "ident" => "bf2_eu1", "name" => "NRNS-GAMES.COM! 247 Karkand", "ip" => "localhost", "port" => 80, "auth_key" => "test"]);

    }

    public function down() {
        $this->deleteTable("servers");
    }
}