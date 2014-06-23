<?php
class Admin extends \DbModel\Base {

    const TYPE_ADMIN = 1;
    const TYPE_DEVELOPER = 2;
    const TYPE_AUTOADMIN = 3;

    public static function validate($password, $hash) {
        return password_verify($password, $hash);
    }

    public function serialize() {
        return $this->_values;
    }
}