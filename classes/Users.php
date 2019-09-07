<?php

class Users extends Table
{
    public $Id;
    public $FirstName;
    public $LastName;

    protected static $TABLE_NAME = 'users';

    public function login($userName, $password)
    {
        $database = new Database();
        $users = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME ."
                    WHERE  
                      UserName = " . $database->quote($userName) . "
                    AND
                      Password = " . $database->quote(md5($password))
        );
        $database->close();

        $response = array();

        if($users && $users->num_rows > 0)
        {
            while ($row = $users->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $this->loadById($response[0]['Id']);
    }

    public static function getUsers()
    {
        $database = new Database();
        $users = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME
        );
        $database->close();

        $response = array();

        if($users && $users->num_rows > 0)
        {
            while ($row = $users->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public function toHtml()
    {
        // TODO: Implement toHtml() method.
    }

    public function toString()
    {
        // TODO: Implement toString() method.
    }

}

?>