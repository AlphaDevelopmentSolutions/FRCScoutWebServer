<?php

class Accounts extends CoreTable
{
    public $Id;
    public $TeamId;
    public $Email;
    public $Username;
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
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ? ";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'Username';
        $args[] = $username;

        $cols[] = 'Password';
        $args[] = sha1($password);


        $sql .= ' LIMIT 1';

        $rows = self::queryRecords($sql, $cols, $args);

        foreach ($rows as $row)
        {
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