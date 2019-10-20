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
        ?>
            <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title header-title" style="flex-shrink: unset !important; -webkit-flex-shrink: unset !important;">
                        <a style="text-decoration: none; color: white;" href="/"><?php echo $this->Title?></a>
                    </span>
                    <div class="mdl-layout-spacer"></div>
                    <div style="font-size: 25px;">
                        <a style="color: white; text-decoration: none; margin: 10px;" href="<?php echo ADMIN_URL ?>account">
                            <i class="fas fa-user-circle"></i>
                        </a>
                        <?php
                        if(loggedIn() && getUser()->IsAdmin == 1)
                        {
                        ?>
                        <a style="color: white; text-decoration: none" href="<?php echo ((empty($this->SettingsUrl)) ? ADMIN_URL . 'list' : $this->SettingsUrl) ?>">
                            <i class="fa fa-cog"></i>
                        </a>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <?php
        //if additional content given, add
        if(!is_null($this->AdditionalContent))
            echo $this->AdditionalContent;

        //if navbar given, add
        if(!is_null($this->NavBar))
        {
            if ($this->NavBar instanceof NavBarArray)
                foreach ($this->NavBar as $navBar)
                    echo $navBar->toString();

            else
                echo $this->NavBar->toString();
        }

        //close the title view
        ?>
        </header>
            <div class="mdl-layout__drawer">
        <?php

        //if event id given, add the nav drawer
        if(!empty($this->Event))
        {
            ?>
            <span class="mdl-layout-title"><?php echo APP_NAME ?></span>
            <nav class="mdl-navigation">
                <a href="<?php echo MATCHES_URL ?>list?eventId=<?php echo $this->Event->BlueAllianceId ?>"
                   class="mdl-navigation__link">Matches</a>
                <a href="<?php echo TEAMS_URL ?>list?eventId=<?php echo $this->Event->BlueAllianceId ?>"
                   class="mdl-navigation__link">Teams</a>
                <a href="<?php echo CHECKLISTS_URL ?>list?eventId=<?php echo $this->Event->BlueAllianceId ?>"
                   class="mdl-navigation__link">Checklist</a>
                <a href="<?php echo STATS_URL ?>stats?eventId=<?php echo $this->Event->BlueAllianceId ?>"
                   class="mdl-navigation__link ">Stats</a>
                <a href="<?php echo EVENTS_URL ?>list?yearId=<?php echo $this->Event->YearId ?>" class="mdl-navigation__link ">Events</a>
            </nav>
            <?php
        }
        //or if the year given, add nav drawer
        else if(!empty($this->Year))
        {
            ?>
            <span class="mdl-layout-title"><?php echo APP_NAME ?></span>
            <nav class="mdl-navigation">
                <a href="<?php echo EVENTS_URL ?>list?yearId=<?php echo $this->Year->Id ?>" class="mdl-navigation__link">Events</a>
                <a href="<?php echo YEARS_URL ?>list" class="mdl-navigation__link">Years</a>
            </nav>
            <?php
        }
        else
        {
            ?>
            <span class="mdl-layout-title"><?php echo APP_NAME ?></span>
            <nav class="mdl-navigation">
                <a href="<?php echo YEARS_URL ?>list" class="mdl-navigation__link">Years</a>
            </nav>
        <?php
        }
        ?>
        <form id="core-sign-out-form" action="">
                    <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect" style="margin: 1.5em; position: absolute !important; bottom: 0 !important; width: 167px;" >
                    Logout
                    </button>
                </form>
            </div>
        <?php
    }
}

?>