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

function getAccessToken() {
    $allHeaders = getallheaders();
    $accessToken = "";
    if (isset($allHeaders['Authorization'])) {
        $accessToken = $allHeaders['Authorization'];
        if (substr($accessToken, 0, 7) == 'Bearer ')
            $accessToken = trim(substr($accessToken, 7));
    }
    else if (isset($_SESSION['accessToken']))
        $accessToken = $_SESSION['accessToken'];
    return $accessToken;
}

function isApplicationToken($accessToken) {
    return strlen($accessToken) == 20;
}

function parseRequest($routeConfig)
{
    $url = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method !== $routeConfig['method']) {
        return false;
    }

    $regExpString = routeExpToRegExp($routeConfig['route']);

    if (preg_match("/$regExpString/", $url, $matches)) {
        $params = getUrlParams($routeConfig, $matches);
        $query = getQueryParams($url);
        $payload = getPayload();
        checkMiddlewares($routeConfig, $params, $query, $payload);

        call_user_func($routeConfig['handler'], [
            "params" => $params,
            "query" => $query,
            "payload" => $payload
        ]);
        return true;
    }
    return false;
}

function handle404()
{
    Response::status(404);
}

function routeExpToRegExp($route) {
    $regExpString = "";
    $parts = explode('/', $route);

    foreach ($parts as $p) {
        $regExpString .= '\/';

        if ($p[0] === ':') {
            $regExpString .= '([a-zA-Z0-9]+)';
        } else {
            $regExpString .= $p;
        }
    }

    $regExpString .= '(\?.*)?$';

    return $regExpString;
}

function getPayload() {
    $payload = file_get_contents('php://input');
    if (strlen($payload)) {
        $payload = json_decode($payload);
    } else {
        $payload = NULL;
    }
    return $payload;
}

function getUrlParams($routeConfig, $matches) {
    $params = [];
    $parts = explode('/', $routeConfig['route']);
    $index = 1;
    foreach ($parts as $p) {
        if ($p[0] === ':') {
            $params[substr($p, 1)] = $matches[$index];
            $index++;
        }
    }
    return $params;
}

function getQueryParams($url) {
    $query = [];
    if (strpos($url, '?')) {
        $queryString = explode('?', $url)[1];
        $queryParts = explode('&', $queryString);
        foreach ($queryParts as $part) {
            if (strpos($part, '=')) {
                $query[explode('=', $part)[0]] = explode('=', $part)[1];
            }
        }
    }
    return $query;
}

function checkMiddlewares($routeConfig, $params, $query, $payload) {
    if (isset($routeConfig['middlewares'])) {
        foreach($routeConfig['middlewares'] as $middlewareName) {
            $didPass = call_user_func($middlewareName, [
                "params" => $params,
                "query" => $query,
                "payload" => $payload
            ]);
            if (!$didPass)
                exit();
        }
    }
}
