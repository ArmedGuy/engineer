<?php
class SiteController extends \Munition\AppController {
    function index() {
        self::render(["template" => "page"]);
    }

    function login($ctx, $params) {
        if(isset($params["username"]) && isset($params["password"])) {
            $admin = Admin::find_by_username($params["username"])->obj();
            if($admin != null && Admin::validate($params["password"], $admin->password)) {
                Session::login($admin);
                self::render(["json" => ["loggedIn" => true, "username" => $admin->username]]);
            } else {
                self::render(["json" => ["loggedIn" => false , "errorMessage" => "Wrong username or password"]]);
            }
        } else {
            self::render(["json" => ["loggedIn" => false, "errorMessage" => "Missing parameters"]]);
        }
    }

    function session() {
        if(Session::logged_in()) {
            self::render(["json" => ["loggedIn" => true, "username" => Session::$account->username, "type" => Session::$account->type]]);
        } else {
            self::render(["json" => ["loggedIn" => false]]);
        }
    }
}