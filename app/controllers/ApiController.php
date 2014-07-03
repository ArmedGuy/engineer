<?php
class ApiController extends \Munition\AppController {
    function register_event($ctx, $params) {
        $server_guid = $_POST["server_guid"];
        $server_key = $_POST["server_key"];
        $server = Server::find_by_guid($server_guid)->obj();
        if($server != null) {
            if($server->auth_key == $server_key) {
                $player_id = $this->process_player();
                $this->process_event($server->id, $player_id);

                self::render(["json" => ["status" => "OK"]]);
            } else {
                self::render(["json" => null]);
            }
        } else {
            self::render(["json" => null]);
        }
    }


    private function process_player() {
        $player_guid = $_POST["player_guid"];
        $player_ip = $_POST["player_ip"];
        $player_name = $_POST["player_name"];

        $now = date("Y-m-d H:i:s");

        $player = Player::find_by_guid($player_guid)->obj();
        if($player != null) {
            $id = $player->id;
            if($player->latest_username != $player_name) {
                PlayerUsername::create(["player_id" => $id, "username" => $player_name, "changed" => $now]);
                $player->latest_username = $player_name;
                $player->save();
            }
            if($player->latest_ip != $player_ip) {
                PlayerIp::create(["player_id" => $id, "ip" => $player_ip, "changed" => $now]);
                $player->latest_ip = $player_ip;
                $player->save();
            }
            return $id;
        } else {
            Player::create(["guid" => $player_guid, "latest_username" => $player_name, "latest_ip" => $player_ip]);
            $id = Player::get()->last->id;

            PlayerUsername::create(["player_id" => $id, "username" => $player_name, "changed" => $now]);
            PlayerIp::create(["player_id" => $id, "ip" => $player_ip, "changed" => $now]);

            return $id;
        }
    }

    private function process_event($server_id, $player_id) {
        $event_type = $_POST["event_type"];
        $event_data = isset($_POST["event_data"]) ? $_POST["event_data"] : [];

        $now = date("Y-m-d H:i:s");
        $event = Event::create(["server_id" => $server_id, "player_id" => $player_id, "type" => $event_type, "submitted" => $now]);
        $event_id = Event::get()->last->id;

        foreach($event_data as $key=>$value) {
            EventData::create(["event_id" => $event_id, "key" => $key, "value" => $value]);
        }
    }
}