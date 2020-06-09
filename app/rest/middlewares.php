<?php

//Used for restricting access to data about exercises
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

//Used to restrict access to a part of the website's functionalities (only logged in users - not applications) 
//are allowed to submit exercises, get their profile data and see the leaderboard.
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

//An user can submit a solution to a level only if he already reached this level.
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

//Checks if application login was deactivated using the administration file.
function ApplicationLoginOn($req) {
    if ($GLOBALS["applicationLogin"] == true)
        return true;

    Response::status(403);
    Response::json([
            "status" => 403,
            "reason" => "Application login is not available."]
    );
    return false;
}