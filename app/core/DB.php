<?php
class DB {

    private $host = "ec2-46-137-84-140.eu-west-1.compute.amazonaws.com";
    private $username;
    private $password;
    private $database = "d610tqcmnd39uo";

    public $connection;

    // get the database connection
    public function __construct() {
        $this->username = $GLOBALS['DB_USER'];
        $this->password = $GLOBALS['DB_PASS'];
        $connection = pg_connect("host=" . $this->host . " dbname=" . $this->database . " user=" . $this->username . " password=" . $this->password)
                or die("Can't connect to database".pg_last_error());
        $GLOBALS['DB_CON'] = $connection;
        return $this->connection;
    }
}
?>