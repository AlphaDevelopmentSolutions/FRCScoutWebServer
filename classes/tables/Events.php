<?php

class Events extends Table
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
     * Overrides parent getObjects method and provides a custom orderby arg
     * @return Events[]
     */
    public static function getObjects()
    {
        return parent::getObjects('StartDate');
    }

    /**
     * Gets teams at event
     * @param null | Matches $match if specified, filters by teams in match
     * @param null | Teams $team if specified, filters by team id
     * @return Teams[]
     */
    public function getTeams($match = null, $team = null)
    {
        require_once(ROOT_DIR . '/classes/tables/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/EventTeamList.php');
        require_once(ROOT_DIR . '/classes/tables/Matches.php');

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

        $rows = self::query($sql, $cols, $args);

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
        require_once(ROOT_DIR . '/classes/tables/Matches.php');
        require_once(ROOT_DIR . '/classes/tables/Teams.php');

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

        $rows = self::query($sql, $cols, $args);

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
        require_once(ROOT_DIR . '/classes/tables/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/RobotInfo.php');

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

        $rows = self::query($sql, $cols, $args);

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
        require_once(ROOT_DIR . '/classes/tables/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/ScoutCardInfo.php');

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

        $rows = self::query($sql, $cols, $args);

        foreach($rows as $row)
            $response[] = ScoutCardInfo::withProperties($row);


        return $response;
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
                            <a href="/match-list.php?eventId=' . $this->BlueAllianceId . '" class="mdl-button">View</a>
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