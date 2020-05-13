<?php

class User 
{
    public static function loginUser($username, $avatar) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Access Token" FROM public."Users" where "Username"=\'' . $username . '\'';
        $result = pg_query($connection, $query);
        $row = pg_fetch_row($result);
        if ($row)
            return $row[0];
        self::registerUser($username, $avatar);
    }

    public static function registerUser($username, $avatar) {
        $connection = $GLOBALS['DB_CON'];
        $data = array("Username" => $username, "Avatar" => $avatar, "Access Token" => uniqid());
        $res = pg_insert($connection, 'Users', $data);
        if ($res) {
            echo "POST data is successfully logged\n";
        } else {
            echo "User must have sent wrong inputs\n";
        }
        return $data['Access Token'];
    }

    public static function isValidAccessToken($accessToken) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Username" FROM public."Users" where "Access Token"=\'' . $accessToken . '\'';
        $result = pg_query($connection, $query);
        $row = pg_fetch_row($result);
        if ($row)
            return true;
        return false;
    }

    public static function getUserData($accessToken) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Username", "Avatar", "html_level", "css_level", "speed_score", "correctness_score", "Access Token" 
                  FROM public."Users" where "Access Token"=\'' . $accessToken . '\'';
        $result = pg_query($connection, $query);
        $row = pg_fetch_row($result);
        if ($row) {
            return array(
                'username'=> $row[0],
                'avatar' => $row[1],
                'html_level' => $row[2],
                'css_level' => $row[3],
                'speed_score' => $row[4],
                'correctness_score' => $row[5],
                'accessToken' => $row[6]);
        }
        return array();
    }

    public static function getCurrentLevel($accessToken, $world) {
        $connection = $GLOBALS['DB_CON'];

        if ($world == "HTML")
            $query = 'SELECT "html_level" FROM public."Users" where "Access Token"=\'' . $accessToken . '\'';
        else
            $query = 'SELECT "css_level" FROM public."Users" where "Access Token"=\'' . $accessToken . '\'';

        $result = pg_query($connection, $query);
        $row = pg_fetch_row($result);
        return $row[0];
    }

    public static function getLeaderboard($leaderboardType) {
        $connection = $GLOBALS['DB_CON'];
        if ($leaderboardType == "speed")
            $orderingCriteria = "speed_score";
        else
            $orderingCriteria = "correctness_score";

        $query = 'SELECT "Username", "Avatar", "' . $orderingCriteria . '" FROM public."Users"
                  ORDER BY "' . $orderingCriteria . '" DESC';

        $leaderboard = array();
        $result = pg_query($connection, $query);
        while ($row = pg_fetch_row($result)) {
            $userData = array();
            $userData['username'] = $row[0];
            $userData['avatar'] = $row[1];
            $userData[$orderingCriteria] = $row[2];
            array_push($leaderboard, $userData);
        }
        return $leaderboard;
    }
}
