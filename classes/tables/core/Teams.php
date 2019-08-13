<?php

class Teams extends CoreTable
{
    public $Id;
    public $Name;
    public $City;
    public $StateProvince;
    public $Country;
    public $RookieYear;
    public $FacebookURL;
    public $TwitterURL;
    public $InstagramURL;
    public $YoutubeURL;
    public $WebsiteURL;
    public $ImageFileURI;

    public static $TABLE_NAME = 'teams';

    /**
     * Returns the URI of the teams profile image
     * @param $year Years to get the image from
     * @return RobotMedia
     */
    public function getProfileImage($year)
    {
        require_once(ROOT_DIR . '/classes/tables/local/RobotMedia.php');

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ? ORDER BY ! DESC LIMIT 1";
        $cols[] = RobotMedia::$TABLE_NAME;
        $cols[] = 'TeamId';
        $args[] = $this->Id;
        $cols[] = 'YearId';
        $args[] = $year->Id;
        $cols[] = 'Id';

        $rows = self::queryRecords($sql, $cols, $args, LocalTable::$DB_NAME);

        foreach ($rows as $row)
        {
            require_once(ROOT_DIR . "/classes/tables/local/RobotMedia.php");
            return RobotMedia::withProperties($row);
        }
    }

    /**
     * Gets all robot media for this team
     * @param $year Years to get the image from
     * @return RobotMedia[]
     */
    public function getRobotPhotos($year = null)
    {
        require_once(ROOT_DIR . '/classes/tables/local/RobotMedia.php');

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? " . ((empty($year)) ? "" : "AND ! = ?") . " ORDER BY ! DESC";
        $cols[] = RobotMedia::$TABLE_NAME;
        $cols[] = 'TeamId';
        $args[] = $this->Id;

        if(!empty($year))
        {
            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        $cols[] = 'Id';

        $rows = self::queryRecords($sql, $cols, $args, LocalTable::$DB_NAME);

        foreach ($rows as $row)
            $response[] = RobotMedia::withProperties($row);

        return $response;
    }

    /**
     * Returns the object once converted into HTML
     * @param Events $event id of the event
     * @return string
     */
    public function toHtml($event = null)
    {
        require_once(ROOT_DIR . '/classes/tables/core/Events.php');
        require_once(ROOT_DIR . "/classes/tables/core/Years.php");

        $html =
            '<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp team-card">
                <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--white mdl-color-text--white">';

            $robotMedia = $this->getProfileImage(Years::withId($event->YearId));

            $html .=
                    '<div style="height: unset">' .
                    ((empty($robotMedia->FileURI)) ? '' : '<a href="/team-photos.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $this->Id . '">') .
                        '<div class="team-card-image" style="background-image: url(' . ((empty($robotMedia->FileURI)) ? ROBOT_MEDIA_URL . 'frc_logo.jpg' : ROBOT_MEDIA_URL . $robotMedia->FileURI) . ')"></div>' .
                    ((empty($robotMedia->FileURI)) ? '' : '</a>') .
                    '</div>';

            $html .=
                '</header>
                    <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
                        <div class="mdl-card__supporting-text">
                            <h4>' . $this->toString() . '</h4>
                            ' . $this->City . ', ' . $this->StateProvince . ', ' . $this->Country .
                        '</div>
                        <div class="mdl-card__actions">
                            <a href="/team-matches.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $this->Id . '" class="mdl-button">View</a>
                        </div>
                    </div>
                </section>';

            return $html;
    }


    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->Id . ' - ' . $this->Name;
    }
}

?>