<?php
class Player extends \DbModel\Base {
    function relations() {
        $this->hasMany("player_ips");
        $this->hasMany("player_usernames", ["class" => "PlayerUsername"]);
        $this->hasMany("events");
    }

    public function serialize() {
        return $this->_values;
    }
}