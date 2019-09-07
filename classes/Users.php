<?php

class Users
{
    public $Id;
    public $FirstName;
    public $LastName;
    private static $TABLE_NAME = 'users';

    function load($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM '. self::$TABLE_NAME. ' WHERE '.'id = '.$database->quote($id);
        $rs = $database->query($sql);

        if($rs && $rs->num_rows > 0) {
            $row = $rs->fetch_assoc();

            if(is_array($row)) {
                foreach($row as $key => $value){
                    if(property_exists($this, $key)){
                        $this->$key = $value;
                    }
                }
            }

            return true;
        }

        return false;
    }

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

        return $this->load($response[0]['Id']);
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

}

?>