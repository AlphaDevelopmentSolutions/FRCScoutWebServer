<?php

class Users extends Table
{
    public $Id;
    public $FirstName;
    public $LastName;
    public $IsAdmin;

    public static $TABLE_NAME = 'users';

    /**
     * Attempts to login with the provided username and password
     * @param $userName
     * @param $password
     * @return boolean
     */
    public function login($userName, $password)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ? LIMIT 1";
        $cols[] = self::$TABLE_NAME;
        $cols[] = 'UserName';
        $args[] = $userName;
        $cols[] = 'Password';
        $args[] = md5($password);

        $response = self::withProperties(self::query($sql, $cols, $args)[0]);

        $this->Id = $response->Id;
        $this->FirstName = $response->FirstName;
        $this->LastName = $response->LastName;
        $this->IsAdmin = $response->IsAdmin;

        return (!empty($response));
    }

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        // TODO: Implement toHtml() method.
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->FirstName . ' ' . $this->LastName;
    }

}

?>