<?php
class Player extends \DbModel\Base {
    function relations() {
        $this->hasMany("player_ips");
        $this->hasMany("player_usernames", ["class" => "PlayerUsername"]);
        $this->hasMany("events");
    }

    public function serialize() {
      $rtn = $this->_values;
      $rtn["names"] = array_reverse(array_map(function($n) {
        return ["name" => $n->username, "changed" => $n->changed];
      }, $this->player_usernames->toArray()));

      $rtn["ips"] = array_reverse(array_map(function($n) {
        return ["ip" => $n->ip, "changed" => $n->changed];
      }, $this->player_ips->toArray()));
      return $rtn;
    }
}