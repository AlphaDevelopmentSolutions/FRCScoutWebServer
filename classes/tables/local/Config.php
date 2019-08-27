<?php

class  Config extends LocalTable
{
    public $Id;
    public $Key;
    public $Value;

    protected static $TABLE_NAME = 'config';

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        return null;
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return null;
    }

}

?>