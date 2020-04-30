<?php

class User 
{
    public $username;
    public $avatar;

    public function __construct($username, $avatar="") {
        $this->connection = $GLOBALS['DB_CON'];
        $this->username = $username;
        $this->avatar = $avatar;

        echo $this->avatar;

        $query = 'SELECT "Avatar" FROM public."Users" where "Username"=\'' . $this->username . '\'';
        $result = pg_query($this->connection, $query);

        $row = pg_fetch_row($result);
        if (!$row) {
            $data = array("Username" => $this->username, "Avatar" => $this->avatar);

            $res = pg_insert($this->connection, 'Users', $data);
            if ($res) {
                echo "POST data is successfully logged\n";
            } else {
                echo "User must have sent wrong inputs\n";
            }
        }
        else {
            $this->avatar = $row[0];
        }
    }
}

?>