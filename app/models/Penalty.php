<?php
class Penalty extends \DbModel\Base {

    public static $table_name = "penalties";
    const TYPE_WARNING = 1;
    const TYPE_KICK = 2;
    const TYPE_TEMPBAN = 3;
    const TYPE_BAN = 4;

    public function relations() {
        $this->belongs_to("server");
        $this->belongs_to("admin");
    }

    public function serialize() {
        return $this->_values;
    }
}