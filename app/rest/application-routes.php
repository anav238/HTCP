<?php
$applicationRoutes = [
    [
        "method" => "POST",
        "route" => "applications",
        "query" => ["email", "name"],
        "middlewares" => ["ApplicationLoginOn"],
        "handler" => "createApplication"
    ]
];

function createApplication($req) {
    if (!isset($req['query']['email']) || !isset($req['query']['name'])) {
        Response::status(400);
        Response::json([
            "status" => 400,
            "reason" => "To create an application, you must provide your email address and your name."
        ]);
        return;
    }
    $email = $req['query']['email'];
    $password = $req['query']['name'];
    require_once __DIR__.'/../models/Application.php';
    $data = array();
    $data['Access Token'] = Application::registerApplication($email, $password);
    if (!$data['Access Token']) {
        Response::status(500);
        Response::json([
            "status" => 400,
            "reason" => "Could not register application."
        ]);
    }
    else {
        Response::status(200);
        Response::json($data);
    }
}