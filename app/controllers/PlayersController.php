<?php
class PlayersController extends BaseApiController {
  function __construct() {
    parent::__construct();
    $this->beforeAction("static::cache_request", ["not" => []]);
  }
  function index() {
      $q = Player::get();
      $q->limit(100);
      $q->select("players.*");
      $q->order("id DESC");
      $q->group("id");

      $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
      $q->offset(($page-1) * 30);

      if(isset($_GET["text"])) {
        $t = "%" . $_GET["text"] . "%";
        $q->where("guid LIKE ? OR latest_username LIKE ? OR latest_ip LIKE ?", $t, $t, $t);
      }
      /* search params */
      if(isset($_GET["name"])) {
          $q->joins("player_usernames")->where("player_usernames.username LIKE ?", "%".$_GET["name"]."%");
      }
      if(isset($_GET["ip"])) {
          $q->joins("player_ips")->where("player_ips.ip LIKE ?", "%".$_GET["ip"]."%");
      }

      /* end search params */

      $players = array_map(function($p) {
          $rtn = $p->toArray();
          $rtn["ips"] = PlayerIp::where(["player_id" => $p->id])->all()->toArray();
          $rtn["names"] = PlayerUsername::where(["player_id" => $p->id])->all()->toArray();
          $rtn["last_seen"] = Event::where(["player_id" => $p->id])->order("id DESC")->first->submitted;
          return $rtn;
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

  function online() {
    $players = [];
    $pData = [];
    $now = date("Y-m-d H:i:s");
    $events = Event::sql("SELECT * FROM events WHERE type IN ('joined', 'left', 'kicked', 'banned') HAVING TIMEDIFF('$now', submitted) < MAKETIME(0, 30, 0) ORDER BY id DESC");
    foreach($events as $event) {
      if($event->type == 'joined' && !in_array($event->player_id, $players)) {
        $players[] = $event->player_id;
        $pData[$event->player_id] = [$event->server_id, $event->submitted];
      }
    }

    self::render(["json" => array_map(function($p) use ($pData) {
        $rtn = $p->toArray();
        $rtn["latest_server"] = Server::find($pData[$p->id][0])->toArray();
        $rtn["joined"] = $pData[$p->id][1];

        return $rtn;
      }, Player::where(["id" => $players])->all->toArray())]);
  }

  function similar($ctx, $params) {
    $id = $params["player_id"];
    $player = Player::find($id)->obj();
    $ips = array_map(function($pip) {
      return $pip->ip;
    }, $player->player_ips);
    if($player != null) {
      $players = Player::get()->select("players.*")->joins("player_ips")->where(["player_ips.ip" => $ips])->whereNot(["player_id"])->all();
      self::render(["json" => $players->toRowsArray()]);
    } else {
      self::render(["json" => []]);
    }
  }





  /// PLAYERS STATISTICS

  function stats_types($ctx, $params) {
    $id = $params["player_id"];

    $types = Event::sql("SELECT type, count(type) as numevents FROM events WHERE player_id = $id AND type NOT IN ('sayall','sayteam') GROUP BY type");
    self::render(["json" => $types->toRowsArray()]);

  }

  function stats_servers($ctx, $params) {
    $id = $params["player_id"];

    $servers = Event::sql("SELECT DISTINCT servers.name AS server_name, COUNT(server_id) AS favweight FROM events JOIN servers on events.server_id = servers.id WHERE player_id = $id GROUP BY server_id");
    self::render(["json" => $servers->toRowsArray()]);
  }

  function stats_toaverage($ctx, $params) {
    $id = $params["player_id"];

    $playerCounts = [];
    $averageCounts = [];

    $types = Event::select("type")->group("type")->whereNot(["type" => ["sayall", "sayteam"]])->all();
    foreach($types as $t) {
      $counts = Event::sql("SELECT player_id, COUNT(type) as numevents FROM events  WHERE type = '{$t->type}' GROUP BY player_id ORDER BY id DESC LIMIT 20000");
      $averageCounts[$t->type] = $this->calculate_average(array_map(function($c) {
        return $c->numevents;
      }, $counts->toArray()));

      $plrCount = Event::select("COUNT(type) as numEvents")->where(["player_id" => $id, "type" => $t->type])->first;
      $playerCounts[$t->type] = $plrCount->numEvents;
    }

    self::render(["json" => ["player" => $playerCounts, "average" => $averageCounts]]);
  }

  function stats_hours($ctx, $params) {
    $id = $params["player_id"];
    $hours = [];
    for($i = 0; $i < 24; $i++) {
      $hours[$i] = 0;
    }
    foreach(Event::sql("SELECT HOUR(submitted) as hourofday, COUNT(submitted) as numevents FROM events WHERE player_id = $id GROUP BY hourofday")->toArray() as $h) {
      $hours[($h->hourofday)] = (int)($h->numevents);
    }
    self::render(["json" => $hours]);
  }



  private function calculate_average($arr) {
    $count = count($arr); //total numbers in array
    $total = 0;
    foreach ($arr as $value) {
      $total = $total + $value; // total value of array numbers
    }
    $average = ($total/$count); // get average value
    return $average;
  }
}