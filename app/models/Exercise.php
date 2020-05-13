<?php
class Exercise {

    public static function getSpecificExercise($type, $level) {
        $connection = $GLOBALS['DB_CON'];
        $type = strtoupper($type);

        $query = 'SELECT "Description", "Problem" FROM public."Exercises" where "Type"=\'' . $type .
            '\' and "Level"=' . $level;
        $result = pg_query($connection, $query);

        $data = [];
        while ($row = pg_fetch_row($result)) {
            $data['level'] = $level;
            $data['description'] = $row[0];
            $data['problem'] = $row[1];
        }
        return $data;
    }

    public static function getAvailableExercisesOfType($accessToken, $type) {
        $connection = $GLOBALS['DB_CON'];
        $type = strtoupper($type);

        $currentUserLevel = User::getCurrentLevel($accessToken, $type);
        $query = 'SELECT "Level", "Description", "Problem" FROM public."Exercises" where "Type"=\'' . $type . '\'
                  and "Level"<=' . $currentUserLevel . ' ORDER BY "Level" ASC';
        $result = pg_query($connection, $query);

        $data = [];
        while ($row = pg_fetch_row($result)) {
            $exercise = [];
            $exercise['level'] = $row[0];
            $exercise['description'] = $row[1];
            $exercise['problem'] = $row[2];
            array_push($data, $exercise);
        }
        return $data;
    }

    public static function checkExerciseSolution($type, $level, $solution) {
        $connection = $GLOBALS['DB_CON'];

        $query = 'SELECT array_to_json("Solution") FROM public."Exercises" WHERE "Type"=\'' . $type
            . '\' and "Level"=' . $level;

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
}