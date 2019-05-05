<?php

class Users extends Table
{
    public $Id;
    public $FirstName;
    public $LastName;

    protected static $TABLE_NAME = 'users';

    /**
     * Attempts to login with the provided username and password
     * @param $userName
     * @param $password
     * @return boolean
     */
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
                $response[] = self::withProperties($row);
            }
        }

        $this->Id = $response[0]->Id;
        $this->FirstName = $response[0]->FirstName;
        $this->LastName = $response[0]->LastName;

        return (!empty($response));
    }

    /**
     * Gets all users in the database
     * @return Users[]
     */
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
        return $this->FirstName . " " . $this->LastName;
    }

}

?>