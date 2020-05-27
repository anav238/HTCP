<?php
class Exercise {

    public static function getExerciseById($accessToken, $id) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Type", "Level", "Description", "Problem", "Attempts", "ExtraHTML", "Solution"
                        FROM public."Exercises" where "ID"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($id));

        $data = [];
        while ($row = pg_fetch_row($result)) {
            $data['type'] = $row[0];
            $data['level'] = $row[1];
            $data['description'] = $row[2];
            $data['problem'] = $row[3];
            $data['attempts'] = $row[4];
            $data['extraHTML'] = $row[5];
            if (isApplicationToken($accessToken))
                $data['solution'] = $row[6];
        }
        if (!isApplicationToken($accessToken))
            self::markExerciseAsOpened($accessToken, $id);
        return $data;
    }

    public static function getSpecificExercise($accessToken, $type, $level) {
        $connection = $GLOBALS['DB_CON'];
        $type = strtoupper($type);
        $query = 'SELECT "ID", "Description", "Problem", "Attempts", "ExtraHTML", "Solution"
                    FROM public."Exercises" where "Type"=$1 and "Level"=$2';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($type, $level));

        $data = [];
        if ($row = pg_fetch_row($result)) {
            $data['type'] = $type;
            $data['level'] = $level;
            $data['id'] = $row[0];
            $data['description'] = $row[1];
            $data['problem'] = $row[2];
            $data['attempts'] = $row[3];
            $data['extraHTML'] = $row[4];
            if (isApplicationToken($accessToken))
                $data['solution'] = $row[5];
            else
                self::markExerciseAsOpened($accessToken, $data['id']);
        }
        return $data;
    }

    public static function getAvailableExercisesOfType($accessToken, $type) {
        $connection = $GLOBALS['DB_CON'];
        $type = strtoupper($type);

        if (isApplicationToken($accessToken))
            $currentUserLevel = self::getMaxLevelOfType($type);
        else
            $currentUserLevel = User::getCurrentLevel($accessToken, $type);
        $query = 'SELECT "ID", "Type", "Level", "Description", "Problem", "Attempts", "ExtraHTML", "Solution"
                        FROM public."Exercises" where "Type"=$1 and "Level"<=$2 ORDER BY "Level" ASC';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($type, $currentUserLevel));
        $isApplication = isApplicationToken($accessToken);
        $data = [];
        while ($row = pg_fetch_row($result)) {
            $exercise = [];
            $exercise['id'] = $row[0];
            $exercise['type'] = $row[1];
            $exercise['level'] = $row[2];
            $exercise['description'] = $row[3];
            $exercise['problem'] = $row[4];
            $exercise['attempts'] = $row[5];
            $exercise['extraHTML'] = $row[6];
            if ($isApplication)
                $exercise['solution'] = $row[7];
            array_push($data, $exercise);
        }
        if (!$isApplication)
            self::markExerciseAsOpened($accessToken, end($data)['id']);
        return $data;
    }

    public static function checkExerciseSolution($id, $solution) {
        $connection = $GLOBALS['DB_CON'];

        $query = 'SELECT array_to_json("Solution") FROM public."Exercises" WHERE "ID"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($id));

        $actual_solution = array();
        while ($row = pg_fetch_row($result))
            $actual_solution = $row[0];

        $actual_solution = json_decode($actual_solution);
        if (count($actual_solution) != count($solution))
            return false;
        for ($i = 0; $i < count($actual_solution); $i++) {
            if ($actual_solution[$i] != $solution[$i])
                return false;
        }
        return true;
    }

    public static function incrementAttempts($accessToken, $id) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'UPDATE public."Exercises" SET "Attempts"="Attempts"+1 where "ID"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($id));

        if (!$result)
            return false;
        $query = 'UPDATE public."ExerciseAttempts" SET "Attempts"="Attempts"+1 where "ExerciseID"=$1 and
                    "UserToken"=$2';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($id, $accessToken));

        if ($result)
            return true;
        return false;
    }

    public static function markExerciseAsOpened($accessToken, $exerciseId) {
        $connection = $GLOBALS['DB_CON'];
        $accessToken = pg_escape_string($accessToken);
        $exerciseId = pg_escape_string($exerciseId);

        $query = 'SELECT "ExerciseID" from public."ExerciseAttempts" 
                    where "ExerciseID"=$1 and "UserToken"=$2';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($exerciseId, $accessToken));

        $row = pg_fetch_row($result);
        if (!$row) {
            $data = array("ExerciseID" => $exerciseId, "UserToken" => $accessToken);
            $result = pg_insert($connection, 'ExerciseAttempts', $data);
        }
    }

    public static function getMaxLevelOfType($type) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT MAX("Level") from public."Exercises" where "Type"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($type));

        $row = pg_fetch_row($result);
        if ($row)
            return $row[0];
        return -1;
    }
}