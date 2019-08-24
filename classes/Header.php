<?php

class Header
{
    private $Title;
    private $AdditionalContent;
    private $NavBar;
    private $Event;
    private $Year;
    private $SettingsUrl;

    /**
     * Header constructor.
     * @param string $Title
     * @param string $AdditionalContent
     * @param NavBar | NavBarArray $NavBar
     * @param Events $Event
     * @param Years $Year
     * @param null $SettingsUrl
     */
    public function __construct($Title, $AdditionalContent = null, $NavBar = null, $Event = null, $Year = null, $SettingsUrl = null)
    {
        $this->Title = $Title;
        $this->AdditionalContent = $AdditionalContent;
        $this->NavBar = $NavBar;
        $this->Event = $Event;
        $this->Year = $Year;
        $this->SettingsUrl = $SettingsUrl;
    }

    /**
     * Converts the header object to a navigable header in HTML
     * @return string
     */
    public function toHtml()
    {
        //add the title and version number
        $html =
            '
            <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title header-title"><a style="text-decoration: none; color: white;" href="' . URL_PATH . '/">' . $this->Title . '</a></span>
                    <div class="mdl-layout-spacer"></div>' .
            ((!loggedIn()) ?
                    '<form  action="/ajax/login.php" method="post" style="">
                            <div class="mdl-textfield mdl-js-textfield login-field-wrapper">
                                <input class="mdl-textfield__input login-field" type="text" name="username" style="background-color: white !important; color: black; ">
                                <label class="mdl-textfield__label" for="username" style="padding-left: .5em; padding-right: .5em; ">Username</label>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield login-field-wrapper">
                                <input class="mdl-textfield__input login-field" type="password" name="password" style="background-color: white !important; color: black;">
                                <label class="mdl-textfield__label" for="password" style="padding-left: .5em; padding-right: .5em; ">Password</label>
                            </div>
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="background-color: var(--color-primary-dark) !important;">
                                Login
                            </button>
                            <input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">
                        </form>'
                    :
                    '<div style="font-size: 25px;">
                        <a style="color: white; text-decoration: none; margin: 10px;" href="' . URL_PATH . '/account.php">
                            <i class="fas fa-user-circle"></i>
                        </a>
                        <a style="color: white; text-decoration: none" href="' . URL_PATH . '/' . ((empty($this->SettingsUrl)) ? 'admin-app.php' : $this->SettingsUrl) . '">
                            <i class="fa fa-cog"></i>
                        </a>
                    </div>') .
                '</div>';

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
        if(!empty($this->Event))
            $html .=
                '
                <div class="mdl-layout__drawer">
                    <span class="mdl-layout-title">' . APP_NAME . '</span>
                    <nav class="mdl-navigation">
                        <a href="' . URL_PATH . '/match-list.php?eventId=' . $this->Event->BlueAllianceId . '" class="mdl-navigation__link">Matches</a>
                        <a href="' . URL_PATH . '/team-list.php?eventId=' . $this->Event->BlueAllianceId . '" class="mdl-navigation__link">Teams</a>
                        <a href="' . URL_PATH . '/checklist-item-list.php?eventId=' . $this->Event->BlueAllianceId . '" class="mdl-navigation__link">Checklist</a>
                        <a href="' . URL_PATH . '/stats.php?eventId=' . $this->Event->BlueAllianceId . '" class="mdl-navigation__link ">Stats</a>
                    </nav>
                        <a href="' . URL_PATH . '/event-list.php?yearId=' . $this->Event->YearId . '" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="margin: 1.5em; position: absolute !important; bottom: 0 !important; width: 167px;" >
                        Change Event
                        </a>
                </div>
                ';

        //or if the year given, add nav drawer
        else if(!empty($this->Year))
            $html .=
                '
                <div class="mdl-layout__drawer">
                    <span class="mdl-layout-title">' . APP_NAME . '</span>
                    <nav class="mdl-navigation">
                        <a href="' . URL_PATH . '/event-list.php?yearId=' . $this->Year->Id . '" class="mdl-navigation__link">Events</a>
                    </nav>
                    <form action="' . URL_PATH . '/year-list.php" style="margin: 1.5em; position: absolute !important; bottom: 0 !important;" method="get">
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 199px;">
                        Change Year
                        </button>
                    </form>
                </div>
                ';

        else
            $html .=
                '
                <div class="mdl-layout__drawer">
                    <span class="mdl-layout-title">' . APP_NAME . '</span>
                    <nav class="mdl-navigation">
                        <a href="' . URL_PATH . '/year-list.php" class="mdl-navigation__link">Years</a>
                    </nav>
                </div>';

        return $html;
    }
}

?>