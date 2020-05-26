<?php
class DB {

    private $host = "ec2-54-247-122-209.eu-west-1.compute.amazonaws.com";
    private $username;
    private $password;
    private $database = "dd02l50juhgo7s";

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
