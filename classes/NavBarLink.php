<?php

class NavBarLink
{
    public $Title;
    public $Link;
    public $IsActive;

    /**
     * NavBarLink constructor.
     * @param string $Title
     * @param string $Link
     * @param boolean $IsActive
     */
    public function __construct($Title, $Link, $IsActive = false)
    {
        $this->Title = $Title;
        $this->Link = URL_PATH . $Link;
        $this->IsActive = $IsActive;
    }

    /**
     * Converts the navbar link into HTML
     * @return string
     */
    public function toString()
    {
        $html =
            '
             <a href="' . $this->Link . '" class="mdl-layout__tab ' . (($this->IsActive) ? 'is-active' : '') . '">' . $this->Title . '</a>
            ';

        return $html;
    }

}