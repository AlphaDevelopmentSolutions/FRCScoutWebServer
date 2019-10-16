<?php

class Events extends CoreTable
{
    public $Id;
    public $BlueAllianceId;
    public $Name;
    public $City;
    public $StateProvince;
    public $Country;
    public $StartDate;
    public $EndDate;
    public $YearId;

    public static $TABLE_NAME = 'events';

    /**
     * Overrides parent withId method and provides a custom column name to use when loading
     * @param int|string $id
     * @return Events
     */
    public static function withId($id)
    {
        return parent::withId($id, 'BlueAllianceId');
    }

    /**
     * Retrieves objects from the database
     * @param Years | null $year if specified, filters by id
     * @param Teams | null $team if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return Events[]
     */
    public static function getObjects($year = null, $team = null, $orderBy = 'StartDate', $orderDirection = 'DESC')
    {
        $whereStatment = "";
        $cols = array();
        $args = array();

        //if year specified, filter by year
        if(!empty($year))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        //if year specified, filter by team
        if(!empty($team))
        {
            require_once(ROOT_DIR . "/classes/tables/core/EventTeamList.php");

            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! IN (SELECT ! FROM ! WHERE ! = ? GROUP BY !) ";
            $cols[] = 'BlueAllianceId';
            $cols[] = 'EventId';
            $cols[] = EventTeamList::$TABLE_NAME;
            $cols[] = 'TeamId';
            $cols[] = 'EventId';
            $args[] = $team->Id;
        }

        return parent::getObjects($whereStatment, $cols, $args, $orderBy, $orderDirection);
    }

    /**
     * Gets teams at event
     * @param null | Matches $match if specified, filters by teams in match
     * @param null | Teams $team if specified, filters by team id
     * @return Teams[]
     */
    public function getTeams($match = null, $team = null)
    {
        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/core/EventTeamList.php');
        require_once(ROOT_DIR . '/classes/tables/core/Matches.php');

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! IN (SELECT ! FROM ! WHERE ! = ?)";
        $cols[] = Teams::$TABLE_NAME;
        $cols[] = 'Id';
        $cols[] = 'TeamId';
        $cols[] = EventTeamList::$TABLE_NAME;
        $cols[] = 'EventId';
        $args[] = $this->BlueAllianceId;

        //if match specified, filter by match
        if(!empty($match))
        {
            $sql .= " AND ( ! IN (SELECT ! FROM ! WHERE ! = ?) OR ";
            $sql .= " ! IN (SELECT ! FROM ! WHERE ! = ?) OR ";
            $sql .= " ! IN (SELECT ! FROM ! WHERE ! = ?) OR ";
            
            $sql .= " ! IN (SELECT ! FROM ! WHERE ! = ?) OR ";
            $sql .= " ! IN (SELECT ! FROM ! WHERE ! = ?) OR ";
            $sql .= " ! IN (SELECT ! FROM ! WHERE ! = ?))";

            $cols[] = 'Id';
            $cols[] = 'BlueAllianceTeamOneId';
            $cols[] = Matches::$TABLE_NAME;
            $cols[] = 'Key';
            $args[] = $match->Key;

            $cols[] = 'Id';
            $cols[] = 'BlueAllianceTeamTwoId';
            $cols[] = Matches::$TABLE_NAME;
            $cols[] = 'Key';
            $args[] = $match->Key;

            $cols[] = 'Id';
            $cols[] = 'BlueAllianceTeamThreeId';
            $cols[] = Matches::$TABLE_NAME;
            $cols[] = 'Key';
            $args[] = $match->Key;

            $cols[] = 'Id';
            $cols[] = 'RedAllianceTeamOneId';
            $cols[] = Matches::$TABLE_NAME;
            $cols[] = 'Key';
            $args[] = $match->Key;

            $cols[] = 'Id';
            $cols[] = 'RedAllianceTeamTwoId';
            $cols[] = Matches::$TABLE_NAME;
            $cols[] = 'Key';
            $args[] = $match->Key;

            $cols[] = 'Id';
            $cols[] = 'RedAllianceTeamThreeId';
            $cols[] = Matches::$TABLE_NAME;
            $cols[] = 'Key';
            $args[] = $match->Key;
        }

        //if team specified, filter by team
        if(!empty($team))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'Id';
            $args[] = $team->Id;
        }

        $sql .= " ORDER BY ! ASC";
        $cols[] = 'Id';

        $rows = self::queryRecords($sql, $cols, $args);

        foreach($rows as $row)
            $response[] = Teams::withProperties($row);

        return $response;
    }

    /**
     * Gets all matches from this event
     * @param Matches | null $match if specified, filters by match
     * @param Teams | null $team if specified, filters by team
     * @return Matches[]
     */
    public function getMatches($match = null, $team = null)
    {
        require_once(ROOT_DIR . '/classes/tables/core/Matches.php');
        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ?";
        $cols[] = Matches::$TABLE_NAME;
        $cols[] = 'EventId';
        $cols[] = 'MatchType';
        $args[] = $this->BlueAllianceId;
        $args[] = Matches::$MATCH_TYPE_QUALIFICATIONS;

        //if team specified, filter by team
        if(!empty($team))
        {
            $sql .= " AND ? IN (!, !, !, !, !, !) ";

            $cols[] = 'BlueAllianceTeamOneId';
            $cols[] = 'BlueAllianceTeamTwoId';
            $cols[] = 'BlueAllianceTeamThreeId';
            $cols[] = 'RedAllianceTeamOneId';
            $cols[] = 'RedAllianceTeamTwoId';
            $cols[] = 'RedAllianceTeamThreeId';
            $args[] = $team->Id;
        }

        //if match specified, filter by match
        if(!empty($match))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'Key';
            $args[] = $match->Key;
        }

        $sql .= " ORDER BY ! DESC";
        $cols[] = 'MatchNumber';

        $rows = self::queryRecords($sql, $cols, $args);

        foreach($rows as $row)
            $response[] = Matches::withProperties($row);

        return $response;
    }

    /**
     * Gets all robot info from this event
     * @param Teams | null $team if specified, filters by team
     * @param RobotInfo | null $robotInfo if specified, filters by robot info
     * @return RobotInfo[]
     */
    public function getRobotInfo($team = null, $robotInfo = null)
    {
        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/local/RobotInfo.php');

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = RobotInfo::$TABLE_NAME;

        $cols[] = 'EventId';
        $args[] = $this->BlueAllianceId;

        //if team specified, filter by team
        if(!empty($team))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        //add the team query if a team was specified
        if(!empty($robotInfo))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'Id';
            $args[] = $robotInfo->Id;
        }

        $sql .= " ORDER BY ! DESC";
        $cols[] = 'Id';

        $rows = self::queryRecords($sql, $cols, $args);

        foreach($rows as $row)
            $response[] = RobotInfo::withProperties($row);


        return $response;
    }

    /**
     * Gets all scout card info from event
     * @param Teams | null $team if specified, filters by team
     * @param ScoutCardInfo | null $scoutCardInfo if specified, filters by scout card info
     * @return ScoutCardInfo[]
     */
    public function getScoutCardInfo($team = null, $scoutCardInfo = null)
    {
        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = ScoutCardInfo::$TABLE_NAME;

        $cols[] = 'EventId';
        $args[] = $this->BlueAllianceId;

        //if team specified, filter by team
        if(!empty($team))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        //add the team query if a team was specified
        if(!empty($scoutCardInfo))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'Id';
            $args[] = $scoutCardInfo->Id;
        }

        $rows = self::queryRecords($sql, $cols, $args);

        foreach($rows as $row)
            $response[] = ScoutCardInfo::withProperties($row);


        return $response;
    }

    /**
     * Gets stats for the specified event
     * @param $scoutCardInfoKeys ScoutCardInfoKeys[] to iterate through
     * @param $scoutCardInfos ScoutCardInfo[] to iterate through
     * @return array of data calculated
     */
    public function getStats($scoutCardInfoKeys, $scoutCardInfos)
    {
        $eventStatsArray = array();
        $eventCardArray = array();

        foreach ($scoutCardInfoKeys as $scoutCardInfoKey)
        {
            $arrayKey = $scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName;

            if ($scoutCardInfoKey->IncludeInStats == '1')
            {
                if (!empty($scoutCardInfos))
                {
                    foreach ($scoutCardInfos as $scoutCardInfo)
                    {
                        if ($scoutCardInfo->PropertyKeyId == $scoutCardInfoKey->Id)
                        {
                            $eventStatsArray[$arrayKey] = ((!empty($eventStatsArray[$arrayKey])) ? $eventStatsArray[$arrayKey] + $scoutCardInfo->PropertyValue : $scoutCardInfo->PropertyValue);

                            $tempCardTotal = ((!empty($eventCardArray[$arrayKey])) ? $eventCardArray[$arrayKey] : 0);
                            $eventCardArray[$arrayKey] = (($scoutCardInfoKey->NullZeros == 1 && $scoutCardInfo->PropertyValue == 0) ? $tempCardTotal : $tempCardTotal + 1);
                        }
                    }

                    if (empty($eventStatsArray[$arrayKey]))
                    {
                        $eventStatsArray[$arrayKey] = 0;

                        $tempCardTotal = ((!empty($eventCardArray[$arrayKey])) ? $eventCardArray[$arrayKey] : 0);
                        $eventCardArray[$arrayKey] = (($scoutCardInfoKey->NullZeros == 1) ? $tempCardTotal : $tempCardTotal + 1);
                    }

                } else
                {
                    $eventStatsArray[$arrayKey] = 0;
                    $eventCardArray[$arrayKey] = 0;
                }
            }
        }

        foreach ($eventStatsArray as $key => $stat)
        {
            $tempCardCount = $eventCardArray[$key];

            $eventStatsArray[$key] = (($tempCardCount != 0) ? round($stat / $tempCardCount, 2) : 0);
        }

        return $eventStatsArray;
    }

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        $html =
            '<div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                        <div class="mdl-card__supporting-text">
                            <h4>' . $this->toString() . '</h4>'
                            . $this->City . ', ' . $this->StateProvince . ', ' . $this->Country . '<br><br>'
                            .  date('F j', strtotime($this->StartDate)) . ' to ' . date('F j', strtotime($this->EndDate)) .
                        '</div>
                        <div class="mdl-card__actions">
                            <a href="' . MATCHES_URL . 'list?eventId=' . $this->BlueAllianceId . '" class="mdl-button">View</a>
                        </div>
                    </div>
                </section>
            </div>';

        return $html;
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->Name;
    }

}

?>