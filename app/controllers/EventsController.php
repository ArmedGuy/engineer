<?php
class EventsController extends \Munition\AppController {
    function index() {
        // show latest events

        $q = Event::get();
        $max = isset($_GET["max"]) ? (int)$_GET["max"] : 30;
        $q->limit($max);
        $q->order("id DESC");
        $q->select("events.*");

        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $q->offset(($page-1) * $max);

        if(isset($_GET["player_id"])) {
            $q->where(["player_id" => $_GET["player_id"]]);
        }

        if(isset($_GET["after"])) {
            $q->where("id > ?", $_GET["after"]);
        }

        /* search params */
        if(isset($_GET["server"])) {
          $q->joins("servers")->where("servers.name LIKE ?", "%" . $_GET["server"] . "%");
        }
        if(isset($_GET["type"])) {
          $q->where(["type" => $_GET['type']]);
        }
        if(isset($_GET["data"])) {
          $q->joins("event_data")->where("event_data.value LIKE ?", "%" . $_GET["data"] . "%");
        }
        /* end search params */

        $events = array_map(function($a) {
            $rtn = $a->toArray();
            // get event data
            $rtn["data"] = [];
            $data = array_map(function($ed) {
                return array_diff_key($ed->toArray(), ["event_id" => ""]);
            }, EventData::where(["event_id" => $a->id])->all->toArray());
            foreach($data as $d) {
                $rtn["data"][$d["key"]] = $d["value"];
            }
            // get player and server
            if(!isset($_GET["noPlayer"]))
                $rtn["player"] = Player::find($a->player_id)->obj()->serialize();
            $rtn["server"] = Server::find($a->server_id)->obj()->serialize();

            return $rtn;
        }, $q->all()->toArray());

        self::render(["json" => $events]);

    }
    function show($ctx, $params) {
        $id = $params["event_id"];
        $event = Event::find($id)->obj();
        if($event != null) {
            self::render(["json" => $event->serialize()]);
        } else {
            self::render(["json" => null]);
        }
    }
}