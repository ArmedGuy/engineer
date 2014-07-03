<?php
class CreatePlayers extends \DbModel\Migration {
    public function up() {
        $this->createTable("players", function($t) {
            $t->integer("id");
            $t->primary("id");

            $t->string("guid");

            $t->string("latest_username");
            $t->string("latest_ip");
        });
    }

    public function down() {
    }
}
