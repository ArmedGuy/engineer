<?php
class CreateEvents extends \DbModel\Migration {
    public function up() {
        $this->createTable("events", function($t) {
            $t->integer("id");
            $t->primary("id");

            $t->integer("server_id");
            $t->index("server_id");

            $t->integer("player_id");
            $t->index("player_id");

            $t->string("type");
            $t->index("type");

            $t->timestamp("submitted");
        });
    }

    public function down() {
        $this->deleteTable("events");
    }
}
