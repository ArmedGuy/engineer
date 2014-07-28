<?php
$r->scope("ptOS", function($r) {
  $r->get("/", "site#index");

  $r->get("/session", "site#session");
  $r->post("/login", "site#login");

  $r->post("/api/register_event", "api#register_event");

  $r->get("/players/online", "players#online");

  $r->pattern("player_id", "[0-9]+");
  $r->scope("players", function($r) {
    $r->get("/:player_id/similar", "players#similar");
    // player stats
    $r->get("/:player_id/stats/types", "players#stats_types");
    $r->get("/:player_id/stats/servers", "players#stats_servers");
    $r->get("/:player_id/stats/toaverage", "players#stats_toaverage");
  });
  $r->resources("players");


  $r->resources("events");
  $r->resources("admins");
  $r->resources("servers");
});