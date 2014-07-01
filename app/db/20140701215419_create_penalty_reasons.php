<?php
class CreatePenaltyReasons extends \DbModel\Migration {
    public function up() {
        $this->createTable("penalty_reasons", function($t) {
            $t->integer("id");
            $t->primary("id");
            $t->integer("reason_id");
            $t->string("name");
        });

        PenaltyReason::create(["reason_id" => 1, "name" => "Bad Guy"]);
    }

    public function down() {
        $this->deleteTable("penalty_reasons");
    }
}
