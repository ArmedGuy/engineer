<?php
class PlayerUsername extends \DbModel\Base {
    public static $table_name = "player_usernames";
    function relations() {
        $this->belongsTo("player");
    }
}