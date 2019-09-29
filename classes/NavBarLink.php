<?php

class NavBarLink
{
    public $Title;
    public $Link;
    public $IsActive;

    /**
     * NavBarLink constructor.
     * @param string $title
     * @param string $link
     * @param boolean $isActive
     */
    public function __construct($title, $link, $isActive = false)
    {
        $this->Title = $title;
        $this->Link = URL_PATH . $link;
        $this->IsActive = $isActive;
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