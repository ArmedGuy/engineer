<?php
$r->scope("engineer", function($r) {
    $r->get("/", "site#index");

    $r->get("/session", "site#session");
    $r->post("/login", "site#login");

    $r->resources("penalties");
    $r->resources("admins");
    $r->resources("servers");
});