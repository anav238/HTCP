<?php

class User 
{
    public static function loginUser($username, $avatar) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Access Token" FROM public."Users" where "Username"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($username));

        $row = pg_fetch_row($result);
        if ($row)
            return $row[0];
        return self::registerUser($username, $avatar);
    }

    public static function registerUser($username, $avatar) {
        $connection = $GLOBALS['DB_CON'];
        $data = array("Username" => $username, "Avatar" => $avatar, "Access Token" => uniqid());
        $res = pg_insert($connection, 'Users', $data, PGSQL_DML_ESCAPE);
        if ($res)
            return $data['Access Token'];
        return null;
    }

    public static function isValidAccessToken($accessToken) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Username" FROM public."Users" where "Access Token"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($accessToken));
        $row = pg_fetch_row($result);
        if ($row)
            return true;
        return false;
    }

    public static function getUserData($accessToken) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Username", "Avatar", "html_level", "css_level", "speed_score", "correctness_score", "Access Token" 
                  FROM public."Users" where "Access Token"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($accessToken));

        $row = pg_fetch_row($result);
        if ($row) {
            return array(
                'username'=> $row[0],
                'avatar' => $row[1],
                'html_level' => $row[2],
                'css_level' => $row[3],
                'speed_score' => round($row[4], 2),
                'correctness_score' => round($row[5], 2),
                'accessToken' => $row[6]);
        }
        return array();
    }

    public static function getCurrentLevel($accessToken, $world) {
        $connection = $GLOBALS['DB_CON'];

        if ($world == "HTML")
            $query = 'SELECT "html_level" FROM public."Users" where "Access Token"=$1';
        else
            $query = 'SELECT "css_level" FROM public."Users" where "Access Token"=$1';

        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($accessToken));

        $row = pg_fetch_row($result);
        return $row[0];
    }

    public static function getLeaderboard($leaderboardType) {
        $connection = $GLOBALS['DB_CON'];
        if ($leaderboardType == "speed")
            $orderingCriteria = "speed_score";
        else if ($leaderboardType == "correctness")
            $orderingCriteria = "correctness_score";
        else
            return;
        $query = 'SELECT "Username", "Avatar", "'. $orderingCriteria . '" FROM public."Users" ORDER BY "'
                    . $orderingCriteria . '" DESC';

        $leaderboard = array();
        $result = pg_query($connection, $query);

        while ($row = pg_fetch_row($result)) {
            $userData = array();
            $userData['username'] = $row[0];
            $userData['avatar'] = $row[1];
            $userData[$orderingCriteria] = round($row[2], 2);
            array_push($leaderboard, $userData);
        }
        return $leaderboard;
    }

    public static function levelUpUser($accessToken, $type) {
        $connection = $GLOBALS['DB_CON'];
        if ($type != 'HTML' && $type != 'CSS')
            return false;
        if (self::getCurrentLevel($accessToken, $type) >= Exercise::getMaxLevelOfType($type))
            return false;
        $levelLabel = strtolower($type . '_level');
        $query = 'UPDATE public."Users" SET "' . $levelLabel . '"="' . $levelLabel . '"+1 where "Access Token"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($accessToken));
        if ($result)
            return true;
        return false;
    }

    public static function updateUserScores($accessToken, $solvedExerciseId) {
        $connection = $GLOBALS['DB_CON'];

        $query = 'SELECT "DateOpened", "Attempts" from public."ExerciseAttempts" 
                    where "ExerciseID"=$1 and "UserToken"=$2';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($solvedExerciseId, $accessToken));

        $row = pg_fetch_row($result);
        if ($row) {
            $dateOpened = date_create($row[0]);
            $currentDate = date_create(strtotime($_SERVER['REQUEST_TIME']));
            $attempts = $row[1];

            $correctnessUpdate = round(100 / $attempts, 2);
            $interval = date_diff($dateOpened, $currentDate);
            $speedUpdate = round(100 / $interval->i, 2);

            $query = 'UPDATE public."Users" SET "correctness_score"="correctness_score"+$1, 
                        "speed_score"="speed_score"+$2 where "Access Token"=$3';
            pg_prepare($connection, "", $query);
            $result = pg_execute($connection, "", array($correctnessUpdate, $speedUpdate, $accessToken));
            if ($result)
                return true;
            return false;
        }
        return false;
    }
}
