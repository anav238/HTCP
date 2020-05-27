<?php
class DB {

    private $host = "ec2-54-75-225-52.eu-west-1.compute.amazonaws.com";
    private $username;
    private $password;
    private $database = "d7k3rf2tro9j4g";

    public $connection;

    // get the database connection
    public function __construct() {
        $this->username = getenv("DB_USER");
        $this->password = getenv("DB_PASS");
        $connection = pg_connect("host=" . $this->host . " dbname=" . $this->database . " user=" . $this->username . " password=" . $this->password)
                or die("Can't connect to database".pg_last_error());
        $GLOBALS['DB_CON'] = $connection;
        return $this->connection;
    }
}
?>
