<?php
class ServersController extends \Munition\AppController {
    function index() {

        $q = Server::get();
        $servers = array_map(function($s) {
            return array_diff_key($s->toArray(), ["auth_key" => ""]);;
        }, $q->all()->toArray());
        self::render(["json" => $servers]);
    }

    function show($ctx, $params) {
        $id = $params["server_id"];
        $server = Server::find($id)->obj();
        if($server != null) {
            self::render(["json" => $server->serialize()]);
        } else {
            self::render(["json" => null]);
        }
    }

    function distribution($ctx, $params) {
        $id = $params["server_id"];
        $players = Player::joins("events")->where(["events.server_id" => $id])->select("latest_ip")->group("id")->all();
        $records = [];
        $lookup = new IpLookup();
        foreach($players as $p) {
          try {
            $record = $lookup->lookup($p->latest_ip);
            if(isset($record["location"])) {
              $records[] = ["location" => $record["location"], "ip_address" => $record["traits"]["ip_address"]];
            }
          } catch(Exception $e) {

          }
        }

        self::render(["json" => $records]);
    }
}