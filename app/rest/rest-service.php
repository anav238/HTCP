<?php

require_once __DIR__ . '/../init.php';
require_once __DIR__.'/../models/Exercise.php';
require_once __DIR__.'/../models/User.php';
require_once "./middlewares.php";
require_once "./utils.php";

require_once "./exercise-routes.php";
require_once "./user-routes.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$allRoutes = [
    ...$exerciseRoutes,
    ...$userRoutes
];

foreach ($allRoutes as $routeConfig)
    if (parseRequest($routeConfig))
        exit;

handle404();

