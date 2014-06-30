<?php
class CreatePenalties extends \DbModel\Migration {
    public function up() {
        $this->createTable("penalties", function($t) {
            $t->integer("id");
            $t->primary("id");

            $t->string("player_id", ["length"=>16]);

            $t->string("player_name");
            $t->string("player_ip");

            $t->integer("server_id");

            $t->integer("type");
            $t->integer("duration");
            $t->integer("reason");
            $t->text("comment");

            $t->integer("admin_id");

            $t->timestamp("submitted");
        });

        Penalty::create(["player_id" => 1, "player_name" => "ArmedGuy", "player_ip" => "127.0.0.1", "server_id" => 1,
            "type" => Penalty::TYPE_KICK, "duration" => 0, "reason" => 1, "comment" => "Retard", "admin_id" => 1]);
    }

    public function down() {
        $this->deleteTable("penalties");
    }
}




