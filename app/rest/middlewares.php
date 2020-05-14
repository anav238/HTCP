<?php

function IsLoggedIn($req) {
    $accessToken = getAccessToken();

    if (strlen($accessToken) == 0) {
        Response::status(401);
        Response::json([
                "status" => 401,
                "reason" => "You can only access this route if you're logged in."]
        );
        return false;
    }

    if (User::isValidAccessToken($accessToken))
        return true;

    Response::status(400);
    Response::json([
            "status" => 400,
            "reason" => "The provided access token is invalid."]
    );
    return false;
}

function HasReachedLevel($req) {
    if (isset($req['params']['id'])) {
        $data = Exercise::getExerciseById($req['params']['id']);
        $levelType = $data['type'];
        $level = $data['level'];
    }
    else {
        if (!isset($req['query']['type']) || !isset($req['query']['level']))
            return true;
        $levelType = strtoupper($req['query']['type']);
        $level = $req['query']['level'];
    }

    $currentLevel = User::getCurrentLevel(getAccessToken(), $levelType);
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
    $id = $req['params']['id'];
    $data = Exercise::getExerciseById($id);
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