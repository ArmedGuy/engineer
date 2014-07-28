<?php
class CreatePlayerUsernames extends \DbModel\Migration {
    public function up() {
        $this->createTable("player_usernames", function($t) {
            $t->integer("id");
            $t->primary("id");

            $t->integer("player_id");
            $t->index("player_id");

            $t->string("username");
            $t->timestamp("changed");
        });
    }

    public function down() {
        $this->deleteTable("player_usernames");
    }
}
