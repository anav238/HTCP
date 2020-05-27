<?php

$userRoutes = [
    [
        "method" => "GET",
        "route" => "users/ping",
        "middlewares" => ["HasAccessToken", "IsUser"],
        "handler" => "getUserData"
    ],
    [
        "method" => "GET",
        "route" => "users/speedLeaderboard",
        "middlewares" => ["HasAccessToken", "IsUser"],
        "handler" => "getSpeedLeaderboard"
    ],
    [
        "method" => "GET",
        "route" => "users/correctnessLeaderboard",
        "middlewares" => ["HasAccessToken", "IsUser"],
        "handler" => "getCorrectnessLeaderboard"
    ]
];

function getUserData($req)
{
    $data = User::getUserData(getAccessToken());
    Response::status(200);
    Response::json($data);
}

function getSpeedLeaderboard($req)
{
    $data = User::getLeaderboard("speed");
    Response::status(200);
    Response::json($data);
}

function getCorrectnessLeaderboard($req)
{
    $data = User::getLeaderboard("correctness");
    Response::status(200);
    Response::json($data);
}
