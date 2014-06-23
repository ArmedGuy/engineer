<?php
class Session {
    public static $account = null;
    public static function logged_in() {
        if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
            return true;
        } else {
            return false;
        }
    }

    public static function login($admin) {
        $_SESSION["loggedIn"] = true;
        $_SESSION["admin"] = $admin;
    }

    public static function developer() {
        if(static::logged_in()) {
            if(Session::$account->type == 2) {

            }
        }
    }

    public static function logout() {
        $_SESSION["loggedIn"] = false;
        $_SESSION["admin"] = null;
    }
}
session_start();
if(Session::logged_in()) {
    Session::$account = $_SESSION["admin"];
}