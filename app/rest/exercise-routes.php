<?php

$exerciseRoutes = [
    [
        "method" => "GET",
        "route" => "exercises",
        "middlewares" => ["IsLoggedIn", "HasReachedLevel"],
        "query" => ["type", "level"],
        "handler" => "getExercises"
    ],
    [
        "method" => "GET",
        "route" => "exercises/:type/current",
        "middlewares" => ["IsLoggedIn"],
        "handler" => "getCurrentExerciseOfType"
    ],
    [
        "method" => "POST",
        "route" => "exercises",
        "query" => ["type", "level"],
        "middlewares" => ["IsLoggedIn"],
        "handler" => "submitExercise"
    ]
];

function getExercises($req) {

    if ($_SERVER['REQUEST_METHOD'] != 'GET')
        return;

    if (isset($req['query']['type']) && isset($req['query']['level'])) {
        $data = Exercise::getSpecificExercise($req['query']['type'], $req['query']['level']);
        Response::status(200);
        Response::json($data);
    }

    else if (isset($req['query']['type'])) {
        $data = Exercise::getAvailableExercisesOfType(getAccessToken(), $req['query']['type']);
        Response::status(200);
        Response::json($data);
    }

    else if (isset($req['query']['level'])) {
        Response::status(400);
        Response::json([
            "status" => 400,
            "reason" => "If you provide a level, you must also provide a type as a query parameter."
        ]);
    }

    else {
        $data = [
            ...Exercise::getAvailableExercisesOfType(getAccessToken(), 'HTML'),
            ...Exercise::getAvailableExercisesOfType(getAccessToken(), 'CSS')
        ];
        Response::status(200);
        Response::json($data);
    }
}

function getCurrentExerciseOfType($req) {
    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $type = strtoupper($req['params']['type']);
            $currentLevel = User::getCurrentLevel($_SESSION['accessToken'], $type);
            $data = Exercise::getSpecificExercise($type, $currentLevel);
            Response::status(200);
            Response::json($data);
    }
}

function submitExercise($req) {
    if ($_SERVER['REQUEST_METHOD'] != 'POST')
        return;
    $type = strtoupper($req['query']['type']);
    $level = $req['query']['level'];
    $solution = $req['payload']->solution;
    $result = Exercise::checkExerciseSolution($type, $level, $solution);
    Response::status(200);
    if ($result) {
        Response::json([
            "status" => 200,
            "reason" => "Success!"
        ]);
    }
    else {
        Response::json([
            "status" => 200,
            "reason" => "Wrong solution!"
        ]);
    }
}
