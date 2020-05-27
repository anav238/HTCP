<?php
class DB {

    private $host;
    private $username;
    private $password;
    private $database;

    public $connection;

    // get the database connection
    public function __construct() {
        $this->host = getenv("DB_HOST");
        $this->database = getenv("DB_NAME");
        $this->username = getenv("DB_USER");
        $this->password = getenv("DB_PASS");
        $connection = pg_connect("host=" . $this->host . " dbname=" . $this->database . " user=" . $this->username . " password=" . $this->password)
                or die("Can't connect to database".pg_last_error());
        $GLOBALS['DB_CON'] = $connection;
        return $this->connection;
    }
}