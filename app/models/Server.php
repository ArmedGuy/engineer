<?php
class Server extends \DbModel\Base {
    public function serialize() {
        return array_diff_key($this->_values, ["auth_key" => ""]);
    }
}