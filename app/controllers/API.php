<?php


class API extends Controller
{
    private $connection;
    public function __construct() {
        $this->connection = $GLOBALS['DB_CON'];
    }

    public function index() {}

    public function exercises($type = "", $level = "") {
        $type = strtoupper($type);
        if ($level == "")
            return $this->getArrayOfExercises($type);
        if ($level == "current")
            $level = $this->getCurrentLevelForUser($type);
        if ($type == "HTML" || $type == "CSS") {
            $query = 'SELECT "Description", "Problem" FROM public."Exercises" WHERE "Type"=\'' . $type
                . '\' and "Level"=' . $level;
            $result = pg_query($this->connection, $query);
            $exercise = array();
            while ($row = pg_fetch_row($result)) {
                $exercise["level"] = $level;
                $exercise["description"] = $row[0];
                $exercise["problem"] = $row[1];
            }
            echo json_encode($exercise);
            //http_send_status(200);
        }
        else {
            //http_send_status(404);
            echo json_encode(["message" => "Exercise not found."]);
        }
    }

    private function getArrayOfExercises($type = "") {
        $query = 'SELECT "Description", "Problem" FROM public."Exercises"';
        if ($type == 'HTML' || $type == 'CSS')
            $query .= ' where "Type"=' . $type;
        else {
            //http_send_status(404);
            echo json_encode(["message" => "Exercises not found."]);
        }
        $result = pg_query($this->connection, $query);
        $exercises = array();
        while ($row = pg_fetch_row($result)) {
            $exercise = array();
            $exercise["description"] = $row[0];
            $exercise["problem"] = $row[1];
            $exercises[] = $exercise;
        }
        return json_encode($exercises);
    }

    private function getCurrentLevelForUser($type) {
        $user = $_SESSION['user'];
        if ($type == "HTML")
            $query = 'SELECT "html_level" FROM public."Users"';
        else
            $query = 'SELECT "css_level" FROM public."Users"';
        $query .= ' where "Username"=\'' . $user . '\'';
        $result = pg_query($this->connection, $query);
        $level = 0;
        while ($row = pg_fetch_row($result))
            $level = $row[0];
        return $level;
    }


}