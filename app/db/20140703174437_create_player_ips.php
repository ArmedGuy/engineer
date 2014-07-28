<?php
class CreatePlayerIps extends \DbModel\Migration {
    public function up() {
        $this->createTable("player_ips", function($t) {
            $t->integer("id");
            $t->primary("id");
            $t->integer("player_id");
            $t->index("player_id");

            $t->string("ip");
            $t->timestamp("changed");
        });
    }

    public function down() {
        $this->deleteTable("player_ips");
    }
}
