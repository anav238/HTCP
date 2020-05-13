<?php
require_once "./exercise-routes.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$allHeaders = getallheaders();

$allRoutes = [
    ...$exerciseRoutes // ... sparge array-ul in elementele sale, astfel incat in allRoutes vor fi elem indiv. din exerciseRoutes
];

foreach($allRoutes as $routeConfig) {
    if (parseRequest($routeConfig))
        exit;
}

handle404();

function parseRequest($routeConfig) {
    $method = $_SERVER['REQUEST_METHOD'];
    $url = $_SERVER['REQUEST_URI'];

    if ($method != $routeConfig['method'])
        return false;

    $regExpString = routeExpToRegExp($routeConfig['route']);

    if (preg_match("/$regExpString/", $url, $matches)) {
        //echo "it matches";
        $params = [];
        $parts = explode('/', $routeConfig['route']);

        $index = 1;
        foreach($parts as $p) {
            if ($p[0] == ':') {
                $params[substr($p, 1)] = $matches[$index];
                $index++;
            }

            $payload = file_get_contents("php://input");
            if (strlen($payload))
                $payload = json_decode($payload);
            else
                $payload = NULL;
            call_user_func($routeConfig['handler'], [
                "params" => $params,
                "payload" => $payload]);
            return true;
        }
        return false;
    }

    return false;
}

function routeExpToRegExp($route) {
    $regExpString = "";
    $parts = explode('/', $route);

    foreach($parts as $p) {
        $regExpString .= '\/';
        if ($p[0] == ':')
            $regExpString .= '([a-zA-Z0-9]+)';
        else
            $regExpString .= $p;
    }
    $regExpString .= '$';
    return $regExpString;
}

function handle404() {

}

/*if ($allHeaders["Content-type"] != "application/json") {
http_response_code(400);
echo json_encode(["message" => "Expecting payload as JSON."]);
return;
}*/

header("Content-type: application/json");