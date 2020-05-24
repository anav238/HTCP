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
        "middlewares" => ["IsLoggedIn", "HasReachedLevel"],
        "query" => ["id"],
        "handler" => "submitExercise"
    ]
];

function getExercises($req) {

    if (isset($req['query']['type']) && isset($req['query']['level'])) {
        $data = Exercise::getSpecificExercise(getAccessToken(),$req['query']['type'], $req['query']['level']);
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
    $type = strtoupper($req['params']['type']);
    $currentLevel = User::getCurrentLevel(getAccessToken(), $type);
    $data = Exercise::getSpecificExercise(getAccessToken(), $type, $currentLevel);
    Response::status(200);
    Response::json($data);
}

function submitExercise($req) {
    $id = $req['query']['id'];
    $solution = $req['payload']->solution;
    $result = Exercise::checkExerciseSolution($id, $solution);
    $accessToken = getAccessToken();
    Exercise::incrementAttempts($accessToken, $id);
    $exercise = Exercise::getExerciseById($accessToken, $id);
    $type = $exercise['type'];
    $level = $exercise['level'];

    Response::status(200);
    if ($result) {
        if ($level == User::getCurrentLevel($accessToken, $type))
            User::updateUserScores($accessToken, $id);
        User::levelUpUser($accessToken, $type);
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
