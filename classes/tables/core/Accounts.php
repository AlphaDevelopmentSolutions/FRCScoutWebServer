<?php

class Accounts extends CoreTable
{
    public $Id;
    public $TeamId;
    public $Email;
    public $Username;
    public $Password;
    public $DbId;
    public $ApiKey;
    public $RobotMediaDir;

    public static $TABLE_NAME = 'accounts';

    /**
     * Attempts to login core acc
     * @param CoreDatabase $database
     * @return boolean
     */
    public function login($database)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? LIMIT 1";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'Username';
        $args[] = $this->Username;

        $rows = self::queryRecords($database, $sql, $cols, $args);

        foreach ($rows as $row)
        {
            if(password_verify($this->Password, $row['Password']))
            {
                parent::loadByProperties($row);
                return true;
            }
        }

        return false;
    }

    /**
     * Loads account by API key
     * @param CoreDatabase $database
     * @param $apiKey
     * @return Accounts
     */
    public static function withApiKey($database, $apiKey)
    {
        return self::withId($database, $apiKey, "ApiKey");
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