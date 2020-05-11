<?php


class deprecated extends Controller
{
    private $connection;
    public function __construct() {
        $this->connection = $GLOBALS['DB_CON'];
    }

    public function index() {}

    public function exercises($type = "", $level = "") {
        $allHeaders = getallheaders();
        if ($allHeaders["Content-type"] != "application/json") {
            http_response_code(400);
            echo json_encode(["message" => "Expecting payload as JSON."]);
            return;
        }

        header("Content-type: application/json");
        $type = strtoupper($type);
        if ($type != "" && $type != "HTML" && $type != 'CSS') {
            echo json_encode(["message" => "Invalid exercise type"]);
            return;
        }
        if ($level != "" && $level != "current" && !is_numeric($level)) {
            echo json_encode(["message" => "Invalid level number"]);
            return;
        }
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if ($level == "")
                    echo $this->getArrayOfExercises($type);
                if ($level == "current")
                    $level = $this->getCurrentLevelForUser($type);
                echo $this->getSpecificExercise($type, $level);
                break;
            case 'POST':
                if ($type == "" || $level == "") {
                    http_response_code(404);
                    echo json_encode(["message" => "You must specify a valid exercise type and level to use post."]);
                    return;
                }
                else
                    $request = json_decode(file_get_contents('php://input'), true);
                $solution = $request["solution"];
                echo $this->checkExerciseSolution($type, $level, $solution);
                break;
        }
    }

    private function getSpecificExercise($type, $level) {
        $query = 'SELECT "Description", "Problem" FROM public."Exercises" WHERE "Type"=\'' . $type
            . '\' and "Level"=' . $level;
        $result = pg_query($this->connection, $query);
        $exercise = array();
        while ($row = pg_fetch_row($result)) {
            $exercise["level"] = $level;
            $exercise["description"] = $row[0];
            $exercise["problem"] = $row[1];
        }
        http_response_code(200);
        return json_encode($exercise);
    }

    private function getArrayOfExercises($type = "") {
        $query = 'SELECT "Description", "Problem" FROM public."Exercises"';
        if ($type == 'HTML' || $type == 'CSS')
            $query .= ' where "Type"=' . $type;
        else {
            http_response_code(404);
            return json_encode(["message" => "Exercises not found."]);
        }
        $result = pg_query($this->connection, $query);
        $exercises = array();
        while ($row = pg_fetch_row($result)) {
            $exercise = array();
            $exercise["description"] = $row[0];
            $exercise["problem"] = $row[1];
            $exercises[] = $exercise;
        }
        http_response_code(200);
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

    private function checkExerciseSolution($type, $level, $solution) {
        $query = 'SELECT array_to_json("Solution") FROM public."Exercises" WHERE "Type"=\'' . $type
            . '\' and "Level"=' . $level;
        $result = pg_query($this->connection, $query);
        $actual_solution = array();
        while ($row = pg_fetch_row($result))
            $actual_solution = $row[0];
        $actual_solution = json_decode($actual_solution);
        if (count($actual_solution) != count($solution)) {
            http_response_code(200);
            return json_encode(["message" => "Wrong solution size"]);
        }
        for ($i = 0; $i < count($actual_solution); $i++)
            if ($actual_solution[$i] != $solution[$i]) {
                http_response_code(200);
                return json_encode(["message" => "Wrong solution"]);
            }
        http_response_code(200);
        return json_encode(["message" => "Success"]);
    }

}