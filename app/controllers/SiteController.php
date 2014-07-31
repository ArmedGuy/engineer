<?php
use GeoIp2\Database\Reader;
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

    function ip_location($ctx, $params) {
      $ip = $params["ip_address"];
      $lookup = new IpLookup();
      $result = $lookup->lookup($ip);
      if(isset($result["location"])) {
        $coords = ["lat" => $result["location"]["latitude"], "lng" => $result["location"]["longitude"]];
        $maps = new StaticMaps();
        $map = $maps->fetch($ip, $coords);

        header("Content-Type: image/png");
        die($map);
      }
    }

    function ip_lookup($ctx, $params) {
      $ip = $params["ip_address"];
      $lookup = new IpLookup();
      $record = $lookup->lookup($ip);
      self::render(["json" => $record]);
    }

    function ip_lookup_bulk($ctx, $params) {
      $ips = $params["ips"];
      $records = [];
      $lookup = new IpLookup();
      foreach($ips as $ip) {
        try {
          $record = $lookup->lookup($ip);
          $records[] = $record;
        } catch(Exception $e) {

        }
      }
      self::render(["json" => $records]);
    }
}