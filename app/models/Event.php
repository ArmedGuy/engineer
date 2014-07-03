<?php
class Event extends \DbModel\Base {
    function relations() {
        $this->hasMany("eventData", ["class" => "EventData"]);
        $this->belongsTo("player");
        $this->belongsTo("server");
    }

    public function serialize() {
        $rtn = $this->_values;
        $rtn["data"] = $this->eventData;
        return $rtn;
    }
}