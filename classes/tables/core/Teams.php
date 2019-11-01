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
     * Retrieves objects from the database
     * @param CoreDatabase $database
     * @param Events | null $event if specified, filters by id
     * @param Matches | null $match if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return Teams[]
     */
    public static function getObjects($database, $event = null, $match = null, $orderBy = 'Id', $orderDirection = 'DESC')
    {
        $whereStatement = "";
        $cols = array();
        $args = array();

        //if obj specified, filter by id
        if(!empty($event))
        {
            $whereStatement .= ((empty($whereStatement)) ? "" : " AND ") . " ! IN (SELECT ! FROM ! WHERE ! = ?) ";
            $cols[] = 'Id';
            $cols[] = 'TeamId';
            $cols[] = EventTeamList::$TABLE_NAME;
            $cols[] = 'EventId';
            $args[] = $event->BlueAllianceId;
        }

        //if obj specified, filter by id
        if(!empty($match))
        {
            $whereStatement .= ((empty($whereStatement)) ? "" : " AND ") . " ! IN (?, ?, ?, ?, ?, ?) ";
            $cols[] = 'Id';
            $args[] = $match->BlueAllianceTeamOneId;
            $args[] = $match->BlueAllianceTeamTwoId;
            $args[] = $match->BlueAllianceTeamThreeId;
            $args[] = $match->RedAllianceTeamOneId;
            $args[] = $match->RedAllianceTeamTwoId;
            $args[] = $match->RedAllianceTeamThreeId;
        }

        return parent::getObjects($database, $whereStatement, $cols, $args, $orderBy, $orderDirection);
    }

    /**
     * Gets stats for the specified match
     * @param $matches Matches[] to iterate through
     * @param $scoutCardInfoKeys ScoutCardInfoKeys[] to iterate through
     * @param $scoutCardInfos ScoutCardInfo[] to iterate through
     * @return array of data calculated
     */
    public function getStats($matches, $scoutCardInfoKeys, $scoutCardInfos)
    {
        foreach($matches as $match)
        {
            $tempStatArray = array();

            $filteredScoutCardInfos = array();

            foreach($scoutCardInfos as $scoutCardInfo)
            {
                if($scoutCardInfo->MatchId == $match->Key && $scoutCardInfo->TeamId == $this->Id)
                    $filteredScoutCardInfos[] = $scoutCardInfo;
            }

            foreach ($scoutCardInfoKeys as $scoutCardInfoKey)
            {
                $arrayKey = $scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName;
                
                if($scoutCardInfoKey->IncludeInStats == '1')
                {
                    if(!empty($filteredScoutCardInfos))
                    {
                        foreach($filteredScoutCardInfos as $scoutCardInfo)
                        {
                            if($scoutCardInfo->PropertyKeyId == $scoutCardInfoKey->Id)
                                $tempStatArray[$arrayKey] = $scoutCardInfo->PropertyValue;
                        }

                        if(empty($tempStatArray[$arrayKey]))
                            $tempStatArray[$arrayKey] = 0;
                    }
                    else
                        $tempStatArray[$arrayKey] = 0;
                }
            }
            $teamStatArray[$match->MatchNumber] = $tempStatArray;
        }

        return $teamStatArray;
    }

    /**
     * Returns the object once converted into HTML
     * @param LocalDatabase $database
     * @param Events $event id of the event
     * @return string
     */
    public function toHtml($database = null, $event = null)
    {
        require_once(ROOT_DIR . '/classes/tables/core/Events.php');
        require_once(ROOT_DIR . "/classes/tables/core/Years.php");
        require_once(ROOT_DIR . "/classes/tables/local/RobotMedia.php");

        $robotMedia = RobotMedia::getObjects($database, null, $event, $this)

        ?>
        <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp team-card">
            <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--white mdl-color-text--white">
                <div style="height: unset">
                <?php
                if(empty($robotMedia))
                {
                ?>
                    <a href="<?php echo TEAMS_URL . 'photos?eventId=' . $event->BlueAllianceId . '&teamId=' . $this->Id ?>">
                <?php
                }
                ?>
                    <div class="team-card-image" style="background-image: url(<?php echo ((empty($robotMedia)) ? IMAGES_URL . 'app-icon.png' : ROBOT_MEDIA_THUMBS_URL . $robotMedia[sizeof($robotMedia) - 1]->FileURI) ?>)"></div>

                <?php
                if(empty($robotMedia))
                {
                ?>
                    </a>
                <?php
                }
                ?>
                </div>
            </header>
            <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
                <div class="mdl-card__supporting-text">
                    <h4><?php echo $this->toString() ?></h4>
                    <?php echo $this->City . ', ' . $this->StateProvince . ', ' . $this->Country ?>
                </div>
                <div class="mdl-card__actions">
                    <a href="<?php echo TEAMS_URL . 'match-list?eventId=' . $event->BlueAllianceId . '&teamId=' . $this->Id ?>" class="mdl-button">View</a>
                </div>
            </div>
        </section>

    <?php
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