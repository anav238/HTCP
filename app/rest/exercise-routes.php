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
        "route" => "exercises/:id",
        "middlewares" => ["IsLoggedIn", "HasReachedLevel", "HasNotSolvedLevel"],
        "handler" => "submitExercise"
    ]
];

function getExercises($req) {

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
    $type = strtoupper($req['params']['type']);
    $currentLevel = User::getCurrentLevel(getAccessToken(), $type);
    $data = Exercise::getSpecificExercise($type, $currentLevel);
    $SESSION[$type . '_attempts'] = 0;
    Response::status(200);
    Response::json($data);
}

function submitExercise($req) {
    $id = $req['params']['id'];
    $solution = $req['payload']->solution;
    $result = Exercise::checkExerciseSolution($id, $solution);
    Exercise::incrementAttempts($id);
    $type = Exercise::getExerciseById($id)['type'];

    Response::status(200);
    if ($result) {
        User::levelUpUser(getAccessToken(), $type);
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
