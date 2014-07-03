<?php
class PlayersController extends \Munition\AppController {
    function index() {
        $q = Player::get();
        $q->limit(100);
        $q->select("players.*");

        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $q->offset(($page-1) * 30);

        if(isset($_GET["name"])) {
            $q->joins("player_names")->where("player_usernames.username LIKE ?", "%".$_GET["name"]."%");
        }
        if(isset($_GET["ip"])) {
            $q->joins("player_ips")->where("player_ips.ip LIKE ?", "%".$_GET["ip"]."%");
        }

        $players = array_map(function($p) {
            return $p->toArray();
        }, $q->all()->toArray());
        self::render(["json" => $players]);
    }

    function show($ctx, $params) {
        $id = $params["player_id"];
        $player = Player::find($id)->obj();
        if($player != null) {
            self::render(["json" => $player->serialize()]);
        } else {
            self::render(["json" => null]);
        }
    }
}