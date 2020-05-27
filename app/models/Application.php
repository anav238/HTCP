<?php
class Application
{
    public static function registerApplication($email, $name)
    {
        $connection = $GLOBALS['DB_CON'];
        $accessToken = self::getExistentToken($email);
        if ($accessToken != null)
            return $accessToken;

        try {
            $data = array("Email" => $email, "Name" => $name, "Access Token" => bin2hex(random_bytes(10)));
            $res = pg_insert($connection, 'Applications', $data);
            if ($res)
                return $data['Access Token'];
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function getExistentToken($email)
    {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Access Token" FROM public."Applications" where "Email"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($email));

        $row = pg_fetch_row($result);
        if ($row)
            return $row[0];
        return null;
    }

    public static function isValidAccessToken($accessToken)
    {
        $connection = $GLOBALS['DB_CON'];
        $query = 'SELECT "Name" FROM public."Applications" where "Access Token"=$1';
        pg_prepare($connection, "", $query);
        $result = pg_execute($connection, "", array($accessToken));

        $row = pg_fetch_row($result);
        if ($row)
            return true;
        return false;
    }
}