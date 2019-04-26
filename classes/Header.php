<?php

class Header
{
    private $Title;
    private $AdditionalContent;
    private $NavBar;
    private $EventId;

    /**
     * Header constructor.
     * @param string $Title
     * @param string $AdditionalContent
     * @param NavBar | NavBarArray $NavBar
     * @param string $EventId
     */
    public function __construct($Title, $AdditionalContent, $NavBar, $EventId)
    {
        $this->Title = $Title;
        $this->AdditionalContent = $AdditionalContent;
        $this->NavBar = $NavBar;
        $this->EventId = $EventId;
    }

    /**
     * Converts the header object to a navigable header in HTML
     * @return string
     */
    public function toString()
    {
        //add the title and version number
        $html =
            '
            <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title header-title"><a style="text-decoration: none; color: white;" href="/">' . $this->Title . '</a></span>
                    <div class="mdl-layout-spacer"></div>
                    <div class="version">Version ' . VERSION . '</div>
                </div>
            ';

        //if additional content given, add
        if(!is_null($this->AdditionalContent))
            $html .= $this->AdditionalContent;

        //if navbar given, add
        if(!is_null($this->NavBar))
        {
            if ($this->NavBar instanceof NavBarArray)
                foreach ($this->NavBar as $navBar)
                    $html .= $navBar->toString();

            else
                $html .= $this->NavBar->toString();
        }

        //close the title view
        $html .=
            '
            </header>
            ';

        //if event id given, add the nav drawer
        if(!is_null($this->EventId))
            $html .=
                '
                <div class="mdl-layout__drawer">
                    <span class="mdl-layout-title">' . ((loggedIn()) ? 'Hello, ' . getUser()->FirstName : APP_NAME) . '</span>
                    <nav class="mdl-navigation">
                        <a href="/match-overview.php?eventId=' . $this->EventId . '" class="mdl-navigation__link">Matches</a>
                        <a href="/teams.php?eventId=' . $this->EventId . '" class="mdl-navigation__link">Teams</a>
                        <a href="/stats.php?eventId=' . $this->EventId . '" class="mdl-navigation__link ">Stats</a>
                    </nav>' .
                ((!loggedIn()) ?
                    '<form  action="login.php" method="post" style="padding: 1.5em; position: absolute !important; bottom: 0 !important; width: 197px;">
                            <div class="mdl-textfield mdl-js-textfield">
                                <input class="mdl-textfield__input" type="text" name="username" style="background-color: white !important; color: black; ">
                                <label class="mdl-textfield__label" for="username" style="padding-left: .5em; padding-right: .5em; ">Username</label>
                            </div>
                            <br>
                            <div class="mdl-textfield mdl-js-textfield">
                                <input class="mdl-textfield__input" type="password" name="password" style="background-color: white !important; color: black;">
                                <label class="mdl-textfield__label" for="password" style="padding-left: .5em; padding-right: .5em; ">Password</label>
                            </div>    <br>
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;">
                                Login
                            </button>
                            <input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">
                        </form>'
                    :
                    '<form action="/logout.php?" style="margin: 1.5em; position: absolute !important; bottom: 0 !important;" method="post">
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 199px;">
                        Logout
                        </button>
                        <input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">
                    </form>') . '
                </div>
                ';

        return $html;

    }

}

?>