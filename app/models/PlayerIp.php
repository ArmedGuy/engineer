<?php
class PlayerIp extends \DbModel\Base {
    function relations() {
        $this->belongsTo("player");
    }
}