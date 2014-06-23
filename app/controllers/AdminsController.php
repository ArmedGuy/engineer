<?php
class AdminsController extends \Munition\AppController {
    function index() {
        $admins = array_map(function($s) {
            return $s->toArray();
        }, Admin::all()->getArrayCopy());
        self::render(["json" => $admins]);
    }

    function show($ctx, $params) {
        $id = $params["server_id"];
        $admin = Server::find($id)->obj();
        if($admin != null) {
            self::render(["json" => $admin->serialize()]);
        }
    }
}