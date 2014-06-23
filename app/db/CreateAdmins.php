<?php
class CreateAdmins extends \DbModel\Migration {
    public function up() {
        $this->createTable("admins", function($t) {
            $t->integer("id");
            $t->primary("id");

            $t->string("username");
            $t->string("password");

            $t->integer("type");
        });

        Admin::create(["username" => "ArmedGuy", "password" => password_hash("hello123", PASSWORD_BCRYPT)]);
    }

    public function down() {
        $this->deleteTable("admins");
    }
}