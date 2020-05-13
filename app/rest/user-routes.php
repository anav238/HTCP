<?php

$userRoutes = [
    [
        "method" => "GET",
        "route" => "users/ping",
        "middlewares" => ["IsLoggedIn"],
        "handler" => "getUserData"
    ],
    [
        "method" => "GET",
        "route" => "users/speedLeaderboard",
        "middlewares" => ["IsLoggedIn"],
        "handler" => "getSpeedLeaderboard"
    ],
    [
        "method" => "GET",
        "route" => "users/correctnessLeaderboard",
        "middlewares" => ["IsLoggedIn"],
        "handler" => "getCorrectnessLeaderboard"
    ]
];

function getUserData($req)
{
    if ($_SERVER['REQUEST_METHOD'] != 'GET')
        return;
    $data = User::getUserData(getAccessToken());
    Response::status(200);
    Response::json($data);
}

function getSpeedLeaderboard($req)
{
    if ($_SERVER['REQUEST_METHOD'] != 'GET')
        return;
    $data = User::getLeaderboard("speed");
    Response::status(200);
    Response::json($data);
}

function getCorrectnessLeaderboard($req)
{
    if ($_SERVER['REQUEST_METHOD'] != 'GET')
        return;
    $data = User::getLeaderboard("correctness");
    Response::status(200);
    Response::json($data);
}
