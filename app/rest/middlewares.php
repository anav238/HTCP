<?php

function IsApplicationOrUser($req) {
    $accessToken = getAccessToken();
    if (strlen($accessToken) < 15)
        return IsUser($req);
    if (Application::isValidAccessToken($accessToken))
        return true;

    Response::status(400);
    Response::json([
            "status" => 400,
            "reason" => "The provided access token is invalid."]
    );
    return false;
}

function IsUser($req) {
    $accessToken = getAccessToken();

    if (User::isValidAccessToken($accessToken))
        return true;

    Response::status(400);
    Response::json([
            "status" => 400,
            "reason" => "The provided access token is invalid."]
    );
    return false;
}

function HasAccessToken($req) {
    $accessToken = getAccessToken();
    if (strlen($accessToken) == 0) {
        Response::status(401);
        Response::json([
                "status" => 401,
                "reason" => "You can only access this route if you're logged in."]
        );
        return false;
    }
    return true;
}

function HasReachedLevel($req) {
    $accessToken = getAccessToken();
    if (isset($req['query']['id'])) {
        $data = Exercise::getExerciseById($accessToken, $req['query']['id']);
        $levelType = $data['type'];
        $level = $data['level'];
    }
    else {
        if (!isset($req['query']['type']) || !isset($req['query']['level']))
            return true;
        $levelType = strtoupper($req['query']['type']);
        $level = $req['query']['level'];
    }

    if (isApplicationToken($accessToken))
        return true;

    $currentLevel = User::getCurrentLevel($accessToken, $levelType);
    if ($currentLevel < $level) {
        Response::status(403);
        Response::json([
                "status" => 403,
                "reason" => "You didn't unlock this level yet."]
        );
        return false;
    }
    return true;
}

function HasNotSolvedLevel($req) {
    $id = $req['query']['id'];
    $data = Exercise::getExerciseById(getAccessToken(), $id);
    $levelType = $data['type'];
    $level = $data['level'];

    $currentLevel = User::getCurrentLevel(getAccessToken(), $levelType);
    if ($currentLevel == $level)
        return true;

    Response::status(403);
    Response::json([
            "status" => 403,
            "reason" => "You already solved this exercise."]
    );
    return false;
}