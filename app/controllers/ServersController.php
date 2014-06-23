<?php
class ServersController extends \Munition\AppController {
    function index() {
        $servers = array_map(function($s) {
            return $s->toArray();
        }, Server::all()->getArrayCopy());
        self::render(["json" => $servers]);
    }

    function show($ctx, $params) {
        $id = $params["server_id"];
        $server = Server::find($id)->obj();
        if($server != null) {
            self::render(["json" => $server->serialize()]);
        }
    }
}