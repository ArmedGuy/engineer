<?php
class CreateEvents extends \DbModel\Migration {
    public function up() {
        $this->createTable("events", function($t) {
            $t->integer("id");
            $t->primary("id");

            $t->integer("server_id");
            $t->integer("player_id");

            $t->string("type");

            $t->datetime("submitted");
        });
    }

    public function down() {
        $this->deleteTable("events");
    }
}
