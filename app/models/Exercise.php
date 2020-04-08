<?php
class Exercise {

    private $connection;
    private $table_name = "Exercises";

    public $type;
    public $level;
    public $description;
    public $problem;
    public $solution;

    public function __construct($data = []){
        $this->connection = $GLOBALS['DB_CON'];
        if (!isset($data['level'])) 
            $data['level'] = 1;
        if (!isset($data['type']))
            $data['type'] = HTML; 
        $this->type = $data['type'];
        $this->level = $data['level'];

        $query = 'SELECT "Description", "Problem", "Solution" FROM public."Exercises" where "Type"=\'' . $this->type . 
                                                                                            '\' and "Level"=' . $this->level;
        $result = pg_query($this->connection, $query);

        while ($row = pg_fetch_row($result)) {
            $this->description = $row[0];
            $this->problem = $row[1];
            $this->solution = $row[2];
        }
    }
}