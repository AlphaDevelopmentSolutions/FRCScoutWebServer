<?php

class Teams extends Table
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

    protected static $TABLE_NAME = 'teams';

    /**
     * Returns the URI of the teams profile image
     * @return RobotMedia
     */
    public function getProfileImage()
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? ORDER BY ! DESC LIMIT 1";
        $cols[] = 'robot_media';
        $cols[] = 'TeamId';
        $args[] = $this->Id;
        $cols[] = 'Id';

        $rows = self::query($sql, $cols, $args);

        foreach ($rows as $row)
        {
            require_once("classes/RobotMedia.php");
            return RobotMedia::withProperties($row);
        }
    }

    /**
     * Gets pit cards for a team
     * @param Events $event if specified, filters by event
     * @return PitCards[]
     */
    public function getPitCards($event)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ? ORDER BY ! DESC";
        $cols[] = 'pit_cards';
        $cols[] = 'TeamId';
        $args[] = $this->Id;
        $cols[] = 'EventId';
        $args[] = $event->BlueAllianceId;
        $cols[] = 'Id';

        $rows = self::query($sql, $cols, $args);

        foreach ($rows as $row)
            $response[] = PitCards::withProperties($row);

        return $response;
    }

    /**
     * Gets all robot media for this team
     * @return RobotMedia[]
     */
    public function getRobotPhotos()
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? ORDER BY ! DESC";
        $cols[] = 'robot_media';
        $cols[] = 'TeamId';
        $args[] = $this->Id;
        $cols[] = 'Id';

        $rows = self::query($sql, $cols, $args);

        foreach ($rows as $row)
            $response[] = RobotMedia::withProperties($row);

        return $response;
    }

    /**
     * Returns the team object once converted into HTML
     * @param Events $event id of the event
     * @return string
     */
    public function toHtml($event = null)
    {

        $html =
            '<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp team-card">
                <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--white mdl-color-text--white">';

            $robotMedia = $this->getProfileImage();

            $html .=
                    '<div style="height: unset">' .
                    ((empty($robotMedia->FileURI)) ? '' : '<a href="/team-photos.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $this->Id . '">') .
                        '<div class="team-card-image" style="background-image: url(' . ((empty($robotMedia->FileURI)) ? 'http://scouting.wiredcats5885.ca/assets/robot-media/frc_logo.jpg' : ROBOT_MEDIA_URL . $robotMedia->FileURI) . ')"></div>' .
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
     * Formats the team name for string use
     * EX: 5885 - Villanova WiredCats
     * @return string
     */
    public function toString()
    {
        return $this->Id . ' - ' . $this->Name;
    }
}

?>