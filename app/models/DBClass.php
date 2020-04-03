<?php
class DBClass {

    private $host = "ec2-46-137-84-140.eu-west-1.compute.amazonaws.com";
    private $username = "rngufmgdpjpctb";
    private $password = "eb61abdc385f551ea51d70c8e4b2e5d15b68b26dd0ff223772749982d2287e48";
    private $database = "d610tqcmnd39uo";

    public $connection;

    // get the database connection
    public function getConnection(){

        $connection = pg_connect("host=" . $this->host . " dbname=" . $this->database . " user=" . $this->username . " password=" . $this->password)
                or die("Can't connect to database".pg_last_error());
        $result = pg_query($connection, 'SELECT * FROM public."Exercises"');
        /*while ($row = pg_fetch_row($result)) {
            echo "!";
            echo "Type: $row[0]  Level: $row[1] Description: $row[2]";
            echo "<br />\n";
        }*/
        return $this->connection;
    }
}
?>