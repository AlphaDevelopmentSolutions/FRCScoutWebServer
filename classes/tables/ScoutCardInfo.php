<?php

class ScoutCardInfo extends Table
{
    public $Id;
    public $YearId;
    public $EventId;
    public $MatchId;
    public $TeamId;
    public $CompletedBy;

    public $PropertyValue;
    public $PropertyKeyId;

    public static $TABLE_NAME = 'scout_card_info';

    /**
     * Gets all robot info for a specific team at an event, or in a year
     * @param Years | null $year if specified, filters by year
     * @param Events | null $event if specified, filters by event
     * @param Matches | null $match if specified, filters by match
     * @param Teams | null $team if specified, filters by team
     * @return ScoutCardInfoArray
     */
    public static function forTeam($year = null, $event = null, $match = null, $team = null)
    {
        require_once(ROOT_DIR . '/classes/tables/ScoutCardInfoKeys.php');
        require_once(ROOT_DIR . '/classes/tables/ScoutCardInfoArray.php');

        $scoutCardInfoArray = new ScoutCardInfoArray();

        foreach(ScoutCardInfoKeys::getKeys($year, $event) as $scoutCardInfoKey)
        {
            foreach(self::load($year, $event, $match, $team, $scoutCardInfoKey) as $scoutCardInfo)
                $scoutCardInfoArray[] = $scoutCardInfo;
        }


        return $scoutCardInfoArray;
    }

    /**
     * Gets all robot info for a specific team at an event, or in a year
     * @param Years | null $year if specified, filters by year
     * @param Events | null $event if specified, filters by event
     * @param Matches | null $match if specified, filters by match
     * @param Teams | null $team if specified, filters by team
     * @param ScoutCardInfoKeys $scoutCardInfoKey scout card info key to load
     * @return ScoutCardInfoArray
     */
    private static function load($year = null, $event = null, $match = null, $team = null, $scoutCardInfoKey)
    {

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'PropertyKeyId';
        $args[] = $scoutCardInfoKey->Id;

        //if year specified, filter by year
        if(!empty($year))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        //if event specified, filter by event
        if(!empty($event))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'EventId';
            $args[] = $event->BlueAllianceId;
        }

        //if event specified, filter by event
        if(!empty($match))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'MatchId';
            $args[] = $match->Key;
        }

        //if team specified, filter by team
        if(!empty($team))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        $sql .= ' ORDER BY ! DESC';
        $cols[] = 'MatchId';

        $rows = self::queryRecords($sql, $cols, $args);

        $scoutCardInfoArray = new ScoutCardInfoArray();

        foreach ($rows as $row)
        {
            $obj = new self();
            foreach($row as $key => $value)
            {
                if(property_exists($obj, $key))
                    $obj->$key = $value;

            }

            $scoutCardInfoArray[] = $obj;
        }

        return $scoutCardInfoArray;
    }

    /**
     * @param Events $event
     * @param Teams $team
     * @return int
     */
    public static function getMatchCount($event, $team)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ?";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'EventId';
        $args[] = $event->BlueAllianceId;
        $cols[] = 'TeamId';
        $args[] = $team->Id;

        $sql .= ' GROUP BY ! ';
        $cols[] = 'MatchId';

        return count(self::queryRecords($sql, $cols, $args));
    }

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        return '';
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->PropertyValue;
    }
}

?>