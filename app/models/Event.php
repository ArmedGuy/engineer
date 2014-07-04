<?php
class Event extends \DbModel\Base {

    public static function create_with_data($player_id, $server_id,$event_type, $data) {
        $now = date("Y-m-d H:i:s");
        Event::create(["server_id" => $server_id, "player_id" => $player_id, "type" => $event_type, "submitted" => $now]);
        $event_id = Event::get()->last->id;

        foreach($data as $key=>$value) {
            EventData::create(["event_id" => $event_id, "key" => $key, "value" => $value]);
        }
    }

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