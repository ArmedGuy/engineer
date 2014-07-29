<?php
$r->scope("ptOS", function($r) {
  $r->get("/", "site#index");

  $r->get("/session", "site#session");
  $r->post("/login", "site#login");

  $r->post("/api/register_event", "api#register_event");

  $r->pattern("ip_address", "\\b(?:\\d{1,3}\\.){3}\\d{1,3}\\b");
  $r->get("/iplookup/:ip_address", "site#ip_lookup");


  $r->get("/players/online", "players#online");

  $r->pattern("player_id", "[0-9]+");
  $r->scope("players", function($r) {
    $r->get("/:player_id/similar", "players#similar");
    // player stats
    $r->get("/:player_id/stats/types", "players#stats_types");
    $r->get("/:player_id/stats/servers", "players#stats_servers");
    $r->get("/:player_id/stats/toaverage", "players#stats_toaverage");
    $r->get("/:player_id/stats/hours", "players#stats_hours");
  });
  $r->resources("players");


  $r->resources("events");
  $r->resources("admins");
  $r->resources("servers");
});