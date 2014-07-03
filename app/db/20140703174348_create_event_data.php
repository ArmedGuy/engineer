<?php
class CreateEventData extends \DbModel\Migration {
    public function up() {
        $this->createTable("event_data", function($t) {
            $t->integer("id");
            $t->primary("id");

            $t->integer("event_id");
            $t->string("key");
            $t->string("value");
        });
    }

    public function down() {
        $this->deleteTable("event_data");
    }
}
