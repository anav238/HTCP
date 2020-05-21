<?php
class Exercise {

    public static function getExerciseById($accessToken, $id) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Type", "Level", "Description", "Problem", "Attempts" FROM public."Exercises" where "ID"=\'' . $id . '\'';
        $result = pg_query($connection, $query);

        $data = [];
        while ($row = pg_fetch_row($result)) {
            $data['type'] = $row[0];
            $data['level'] = $row[1];
            $data['description'] = $row[2];
            $data['problem'] = $row[3];
            $data['attempts'] = $row[4];
        }
        self::markExerciseAsOpened($accessToken, $id);
        return $data;
    }

    public static function getSpecificExercise($accessToken, $type, $level) {
        $connection = $GLOBALS['DB_CON'];
        $type = strtoupper($type);

        $query = 'SELECT "ID", "Description", "Problem", "Attempts" FROM public."Exercises" where "Type"=\'' . $type .
            '\' and "Level"=' . $level;
        $result = pg_query($connection, $query);

        $data = [];
        if ($row = pg_fetch_row($result)) {
            $data['type'] = $type;
            $data['level'] = $level;
            $data['id'] = $row[0];
            $data['description'] = $row[1];
            $data['problem'] = $row[2];
            $data['attempts'] = $row[3];
            self::markExerciseAsOpened($accessToken, $data['id']);
        }
        return $data;
    }

    public static function getAvailableExercisesOfType($accessToken, $type) {
        $connection = $GLOBALS['DB_CON'];
        $type = strtoupper($type);

        $currentUserLevel = User::getCurrentLevel($accessToken, $type);
        $query = 'SELECT "ID", "Type", "Level", "Description", "Problem", "Attempts" FROM public."Exercises" where "Type"=\'' . $type . '\'
                  and "Level"<=' . $currentUserLevel . ' ORDER BY "Level" ASC';
        $result = pg_query($connection, $query);

        $data = [];
        while ($row = pg_fetch_row($result)) {
            $exercise = [];
            $exercise['id'] = $row[0];
            $exercise['type'] = $row[1];
            $exercise['level'] = $row[2];
            $exercise['description'] = $row[3];
            $exercise['problem'] = $row[4];
            $exercise['attempts'] = $row[5];
            array_push($data, $exercise);
        }
        return $data;
    }

    public static function checkExerciseSolution($id, $solution) {
        $connection = $GLOBALS['DB_CON'];

        $query = 'SELECT array_to_json("Solution") FROM public."Exercises" WHERE "ID"=\'' . $id . '\'';

        $result = pg_query($connection, $query);
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
        $query = 'UPDATE public."Exercises" SET "Attempts"="Attempts"+1 where "ID"=\'' . $id . '\'';
        $result = pg_query($connection, $query);
        if (!$result)
            return false;
        $query = 'UPDATE public."ExerciseAttempts" SET "Attempts"="Attempts"+1 where "ExerciseID"=\'' . $id . '\' and
                    "UserToken"=\'' . $accessToken . '\'';
        $result = pg_query($connection, $query);
        if ($result)
            return true;
        return false;
    }

    public static function markExerciseAsOpened($accessToken, $exerciseId) {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "ExerciseID" from public."ExerciseAttempts" 
                    where "ExerciseID"=\'' . $exerciseId . '\' and "UserToken"=\'' .
                    $accessToken . '\'';
        $result = pg_query($connection, $query);
        $row = pg_fetch_row($result);
        if (!$row) {
            $data = array("ExerciseID" => $exerciseId, "UserToken" => $accessToken);
            $result = pg_insert($connection, 'ExerciseAttempts', $data);
        }
    }
}