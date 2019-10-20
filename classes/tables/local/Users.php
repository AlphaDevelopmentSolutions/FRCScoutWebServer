<?php

class Users extends LocalTable
{
    public $Id;
    public $FirstName;
    public $LastName;
    public $UserName;
    public $Password;
    public $IsAdmin;

    public static $TABLE_NAME = 'users';

    /**
     * Attempts to login with the provided username and password
     * @param $userName
     * @param $password
     * @return Users
     */
    public function login($userName, $password)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? LIMIT 1";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'Username';
        $args[] = $userName;

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
        ?>
        <div class="center-div-horizontal-outer">
            <div class="center-div-horizontal-inner mdl-card__supporting-text">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input disabled class="mdl-textfield__input" type="text" value="<?php echo $this->FirstName . ' ' . $this->LastName ?>">
                    <label class="mdl-textfield__label" >Name</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input disabled class="mdl-textfield__input" type="text" value="<?php echo $this->UserName ?>">
                    <label class="mdl-textfield__label" >Username</label>
                </div>
            </div>
            <div class="center-div-horizontal-outer">
                <form id="user-sign-out-form" class="center-div-horizontal-inner" action="">
                    <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent material-padding">Logout</button>
                </form>
            </div>
        </div>
    <?php
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