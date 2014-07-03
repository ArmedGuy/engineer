<?php
class EventsController extends \Munition\AppController {
    function index() {
        // show latest events

        $q = Event::get();
        $q->limit(30);
        $q->order("id DESC");

        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $q->offset(($page-1) * 30);

        if(isset($_GET["player"])) {
            $q->where(["player_id" => $_GET["player"]]);
        }

        if(isset($_GET["after"])) {
            $q->where("id > ?", $_GET["after"]);
        }

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