<?php

class Demos extends CoreTable
{
    public $Id;
    public $AccountId;
    public $Expires;

    public static $TABLE_NAME = 'demos';

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {

    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return "Expires " . $this->Expires;
    }

}

?>