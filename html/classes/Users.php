<?php

class Users
{
    public $Id;
    public $FirstName;
    public $LastName;
    private static $TABLE_NAME = 'teams';

    public static function getUsers()
    {
        $database = new Database();
        $users = $database->query(
            "SELECT 
                      * 
                    FROM 
                      users "
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

}

?>