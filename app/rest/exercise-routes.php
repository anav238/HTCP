<?php

class Response {
    static function status($code) {
        http_response_code($code);
    }

    static function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

require_once __DIR__.'/../models/Exercise.php';
require_once __DIR__.'/../models/User.php';

$exerciseRoutes = [
    [
        "method" => "GET",
        "route" => "exercises",
        "middlewares" => ["IsLoggedIn"],
        "query" => ["type", "level"],
        "handler" => "getExercises"
    ],
    [
        "method" => "GET",
        "route" => "exercises/:type/current",
        "handler" => "getCurrentExerciseOfType"
    ],
    [
        "method" => "POST",
        "route" => "exercises",
        "handler" => "submitExercise"
    ]
];

function IsLoggedIn($req) {
    $allHeaders = getallheaders();

    if (isset($allHeaders['Authorization']) || isset($_SESSION['user']))
        return true;
    Response::status(401);
    Response::json([
        "status" => 401,
        "reason" => "You can only access this route if you're logged in."]
    );
    return false;
}

function getExercises($req) {
    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (isset($req['query']['type']) && isset($req['query']['level'])) {
                $data = Exercise::getSpecificExercise($req['query']['type'], $req['query']['level']);
                Response::status(200);
                Response::json($data);
            }
            else if (isset($req['query']['type'])) {
                $data = Exercise::getAllExercisesOfType($req['query']['type']);
                Response::status(200);
                Response::json($data);
            }
            else {
                Response::status(400);
                Response::json([
                    "status" => 400,
                    "reason" => "You must provide a type as a query parameter."
                ]);
            }
            break;
    }
}

function getCurrentExerciseOfType($req) {
    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $type = strtoupper($req['params']['type']);
            $currentLevel = User::getCurrentLevel($_SESSION['user'], $type);
            $data = Exercise::getSpecificExercise($type, $currentLevel);
            Response::status(200);
            Response::json($data);
    }
}

function submitExercise($req) {
    switch($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $type = strtoupper($req['query']['type']);
            $level = $req['query']['level'];
            $solution = $req['payload']->solution;
            $result = Exercise::checkExerciseSolution($type, $level, $solution);
            Response::status(200);
            if ($result) {
                Response::json([
                    "status" => 400,
                    "reason" => "Success!"
                ]);
            }
            else {
                Response::json([
                    "status" => 400,
                    "reason" => "Wrong solution!"
                ]);
            }
    }
}
