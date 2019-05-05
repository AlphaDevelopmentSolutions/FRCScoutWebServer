<?php

class NavBar
{

    private $navBarLinks;

    /**
     * NavBar constructor.
     * @param NavBarLinkArray $navBarLinks
     */
    public function __construct($navBarLinks)
    {
        $this->navBarLinks = $navBarLinks;
    }

    /**
     * Converts the navbar into an HTML document
     * @return string
     */
    public function toString()
    {
        $html =
            '
            <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">';

        foreach ($this->navBarLinks as $navBarLink)
            $html .= $navBarLink->toString();


        $html .=
            '
            </div>
            ';

        return $html;
    }
}

?>