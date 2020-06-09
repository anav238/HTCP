<?php

$exerciseRoutes = [
    [
        "method" => "GET",
        "route" => "exercises",
        "middlewares" => ["HasAccessToken", "IsApplicationOrUser", "HasReachedLevel"],
        "query" => ["type", "level"],
        "handler" => "getExercises"
    ],
    [
        "method" => "GET",
        "route" => "exercises/:type/current",
        "middlewares" => ["HasAccessToken", "IsUser"],
        "handler" => "getCurrentExerciseOfType"
    ],
    [
        "method" => "POST",
        "route" => "exercises",
        "middlewares" => ["HasAccessToken", "IsUser", "HasReachedLevel"],
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
        $data = Exercise::getAvailableExercisesOfLevel(getAccessToken(), $req['query']['type']);
        Response::status(200);
        Response::json($data);
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
    //Function used with the /api/:type/current route, for getting the current level of certain type when a user logs in.
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
    //$result is set to true if the provided solution is correct
    if ($result) {
        if ($level == User::getCurrentLevel($accessToken, $type)) {
            //If the user didn't solve this level before, we update his score and level him up.
            User::updateUserScores($accessToken, $id);
            User::levelUpUser($accessToken, $type);
        }
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
