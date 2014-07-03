<?php
class Event extends \DbModel\Base {
    function relations() {
        $this->hasMany("eventData", ["class" => "EventData"]);
        $this->belongsTo("player");
        $this->belongsTo("server");
    }
}