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
        "route" => "exercise/:type/current",
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
    Response::status(200);

    $type = "HTML";
    $level = 1;
    var_dump($req['query']);

    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $data = getSpecificExercise($type, $level);
            Response::status(200);
            Response::json($data);
            break;
    }
}

function getSpecificExercise($type, $level) {
    $query = 'SELECT "Description", "Problem" FROM public."Exercises" WHERE "Type"=\'' . $type
            . '\' and "Level"=' . $level;
    $connection = $GLOBALS['DB_CON'];
    $result = pg_query($connection, $query);
        $exercise = array();
        while ($row = pg_fetch_row($result)) {
            $exercise["level"] = $level;
            $exercise["description"] = $row[0];
            $exercise["problem"] = $row[1];
        }
        return $exercise;
}


function getTeam($req) {


    // req['payload']

    // DB GET $req['params']['teamId'];

    Response::status(200);
    Response::json($req['params']);



    // echo "Get team {$req['params']['teamId']}";
    // $req['params']['teamId'];


    /// procesare din DB

    // $res -> status(200);
    // http_response_code(200)

    // $res -> json($payload);
    // header("Content-Type: application/json");
    // echo json_encode($payload);
}

function addTeam($req) {
    $modifiedPayload = $req['payload'];
    $modifiedPayload -> id = uniqid();

    Response::status(200);
    Response::json($modifiedPayload);
}


