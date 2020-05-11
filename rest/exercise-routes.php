<?php

class Response {
    static function status($code) {
        http_response_code($code);
    }

    static function json($data) {
        header('Content-Type: application/json');
        echo $data;
    }
}

$exerciseRoutes = [
    [
        "method" => "GET",
        "route" => "/exercises",
        "handler" => "getExercises"
    ],
    [
        "method" => "GET",
        "route" => "/exercises/:exerciseId",
        "handler" => "getExercise"
    ]
];

function getExercises($req) {
    // $req['params']
    //$res -> status(200); // http_response_code(200)
    //$res -> json($payload); //header("Content-Type: application/json");
    //echo json_encode($payload);
    Response::status(200);
    Response::json($req['params']);
    echo "GET ALL EXERCISES";
}

function getExercise($req, $res) {

}