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
}