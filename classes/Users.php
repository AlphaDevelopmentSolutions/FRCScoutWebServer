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
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ?";
        $cols[] = self::$TABLE_NAME;
        $cols[] = 'UserName';
        $args[] = $userName;
        $cols[] = 'Password';
        $args[] = md5($password);

        $rows = self::query($sql, $cols, $args);


        foreach ($rows as $row)
            $response[] = self::withProperties($row);

        $this->Id = $response[0]->Id;
        $this->FirstName = $response[0]->FirstName;
        $this->LastName = $response[0]->LastName;

        return (!empty($response));
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