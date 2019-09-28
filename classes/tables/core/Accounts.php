<?php

class Accounts extends CoreTable
{
    public $Id;
    public $TeamId;
    public $Email;
    public $Username;
    public $Password;
    public $DbId;

    public static $TABLE_NAME = 'accounts';

    /**
     * @param $username
     * @param $password
     * @return static
     */
    public static function login($username, $password)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? LIMIT 1";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'Username';
        $args[] = $username;

        $rows = self::queryRecords($sql, $cols, $args);

        foreach ($rows as $row)
        {
            if(password_verify($password, $row['Password']))
                return self::withProperties($row);
        }
    }

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        return '';
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->TeamId;
    }

}

?>