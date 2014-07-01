<?php
function ensureLoggedIn($ctx) {
    if(Session::logged_in()) {
        return $ctx;
    } else {
        PenaltiesController::render(["json" => ["error" => true, "errorMessage" => "Not logged in"]]);
    }
}
class PenaltiesController extends \Munition\AppController {

    public function __construct() {
        $this->beforeAction("ensureLoggedIn", ["not" => []]);
    }
    function index($ctx) {

        $q = Penalty::get();
        $q->order("id DESC");
        $q->limit(100);
        $q->offset(100 * (isset($_GET["page"]) && $_GET["page"] > 0 ?
            $_GET['page'] - 1 : 0));
        if(isset($_GET["game"])) {
            $server_ids = array_map(function($i) {
                return $i->id;
            }, Server::where(["game_ident" => $_GET["game"]])->select("id")->all->getArrayCopy());

            $q->where(["server_id" => $server_ids]);
        }
        if(isset($_GET["server"])) {
            $server = Server::find_by_ident($_GET["server"])->obj();
            if($server != null) {
                $q->where(["server_id" => $server->id]);
            }
        }
        if(isset($_GET["text"]) && $_GET["text"] != "") {
            $t = "%".$_GET["text"]."%";
            $q->where("player_id LIKE ? OR player_name LIKE ? OR player_ip LIKE ? OR comment LIKE ?", $t, $t, $t, $t);
        }

        if(isset($_GET["after"]) && $_GET["after"] != "") {
            $q->where("id > ?", $_GET["after"]);
        }

        $p = $q->all;
        $penalties = array_map(function($s) {
            return $s->toArray();
        }, $p->getArrayCopy());

        self::render(["json" => $penalties]);
    }

    function show($ctx, $params) {
        $id = $params["penalty_id"];

        $p = Penalty::find($id)->obj();
        if($p != null) {
            $p->serialize();
            self::render(["json" => $p]);
        }
    }
}