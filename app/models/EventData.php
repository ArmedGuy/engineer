<?php
class EventData extends \DbModel\Base {
    public static $table_name = "event_data";

    function relations() {
        $this->belongsTo("event");
    }
}