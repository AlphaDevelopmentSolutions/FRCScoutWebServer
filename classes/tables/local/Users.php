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
     * @return boolean
     */
    public function login($userName, $password)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? LIMIT 1";
        $cols[] = self::$TABLE_NAME;
        $cols[] = 'UserName';
        $args[] = $userName;

        $query = self::queryRecords($sql, $cols, $args);

        if(!empty($query))
        {
            $response = self::withProperties($query[0]);

            $this->Id = $response->Id;
            $this->FirstName = $response->FirstName;
            $this->LastName = $response->LastName;
            $this->UserName = $response->UserName;
            $this->IsAdmin = $response->IsAdmin;
        }

        $success = !empty($response);

        if($success)
            $success = password_verify($password, $response->Password);

        return ($success);
    }

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        $html = '<div style="width: 50%; margin: auto;">
            <div style="height: unset" class="mdl-layout__header-row">
                <div>
                    <h3>Hello,</h3>
                    <h3>' . $this->FirstName . '</h3>
                </div>
                <div class="circle-image" style="margin-left:auto; margin-right:0;">
                    <i style="font-size: 200px" class="fas fa-user-circle"></i>
                </div>
            </div>
            
            <div style="margin: 0 40px;" class="mdl-card__supporting-text">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input disabled class="mdl-textfield__input" type="text" value="' . $this->FirstName . ' ' . $this->LastName .'" name="completedBy">
                    <label class="mdl-textfield__label" >Name</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input disabled class="mdl-textfield__input" type="text" value="' . $this->UserName . '" name="completedBy">
                    <label class="mdl-textfield__label" >Username</label>
                </div>
              
            </div>
            <div class="center-div-horizontal-outer">
                <form class="center-div-horizontal-inner" action="<?php echo AJAX_URL ?>logout.php">
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect">Logout</button>
                </form>
            </div>
        </div>';

        return $html;
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