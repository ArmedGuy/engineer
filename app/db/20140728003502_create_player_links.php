<?php
class CreatePlayerLinks extends \DbModel\Migration {
    public function up() {
      $this->createTable("player_links", function($t) {
        $t->integer("id");
        $t->primary("id");

        $t->integer("player1_id");
        $t->index("player1_id");

        $t->integer("player2_id");
        $t->index("player2_id");
      });
    }

    public function down() {
      $this->deleteTable("player_links");
    }
}
