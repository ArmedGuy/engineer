<?php
class ApiController extends \Munition\AppController {
    function register_event($ctx, $params) {
        $server_guid = $_POST["server_guid"];
        $server_key = $_POST["server_key"];
        $server = Server::find_by_guid($server_guid)->obj();
        if($server != null) {
            if($server->auth_key == $server_key) {
                $player_id = $this->process_player($server->id);
                $this->process_event($server->id, $player_id);

                self::render(["json" => ["status" => "OK"]]);
            } else {
                self::render(["json" => null]);
            }
        } else {
            self::render(["json" => null]);
        }
    }


    private function process_player($server_id) {
        $player_guid = $_POST["player_guid"];
        $player_ip = $_POST["player_ip"];
        $player_name = $_POST["player_name"];

        $now = date("Y-m-d H:i:s");

        $player = Player::find_by_guid($player_guid)->obj();
        if($player != null) {
            $id = $player->id;
            if($player->latest_username != $player_name) {
                PlayerUsername::create(["player_id" => $id, "username" => $player_name, "changed" => $now]);
                $tmp = $player->latest_username;
                $player->latest_username = $player_name;
                $player->save();
                Event::create_with_data($id, $server_id, 'namechanged', ["before" => $tmp, "after" => $player_name]);
            }
            if($player->latest_ip != $player_ip) {
                PlayerIp::create(["player_id" => $id, "ip" => $player_ip, "changed" => $now]);
                $tmp = $player->latest_ip;
                $player->latest_ip = $player_ip;
                $player->save();
                Event::create_with_data($id, $server_id, 'ipchanged', ["before" => $tmp, "after" => $player_ip]);
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

        Event::create_with_data($player_id, $server_id, $event_type, $event_data);
    }
}