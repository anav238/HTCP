<?php
class DBClass {

    private $host = "ec2-46-137-84-140.eu-west-1.compute.amazonaws.com";
    private $username = "rngufmgdpjpctb";
    private $password = "eb61abdc385f551ea51d70c8e4b2e5d15b68b26dd0ff223772749982d2287e48";
    private $database = "d610tqcmnd39uo";

    public $connection;

    // get the database connection
    public function __construct() {

        $connection = pg_connect("host=" . $this->host . " dbname=" . $this->database . " user=" . $this->username . " password=" . $this->password)
                or die("Can't connect to database".pg_last_error());
        $GLOBALS['DB_CON'] = $connection;
        return $this->connection;
    }
}
?>