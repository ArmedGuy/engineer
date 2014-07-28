<?php
$r->scope("ptOS", function($r) {
    $r->get("/", "site#index");

    $r->get("/session", "site#session");
    $r->post("/login", "site#login");

    $r->post("/api/register_event", "api#register_event");

    $r->get("/players/online", "players#online");

    $r->resources("players");
    $r->get("/players/:player_id/similar", "players#similar");

    $r->resources("events");
    $r->resources("admins");
    $r->resources("servers");
});