<?php
class Server extends \DbModel\Base {
    public function serialize() {
        return $this->_values;
    }
}