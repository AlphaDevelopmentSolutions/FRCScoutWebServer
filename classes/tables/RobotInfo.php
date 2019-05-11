<?php

class RobotInfo extends Table
{
    public $Id;
    public $YearId;
    public $EventId;
    public $TeamId;

    public $PropertyState;
    public $PropertyKey;
    public $PropertyValue;

    public static $TABLE_NAME = 'robot_info';

    /**
     * Gets all robot info for a specific team at an event, or in a year
     * @param Years | null $year if specified, filters by year
     * @param Events | null $event if specified, filters by event
     * @param Teams | null $team if specified, filters by team
     * @return RobotInfoArray
     */
    public static function forTeam($year = null, $event = null, $team = null)
    {
        require_once(ROOT_DIR . '/classes/tables/RobotInfoKeys.php');
        require_once(ROOT_DIR . '/classes/tables/RobotInfoArray.php');

        $robotInfoArray = new RobotInfoArray();

        foreach(RobotInfoKeys::getRobotInfoKeys($year, $event) as $propertyKey => $propertyValue)
        {
            foreach(RobotInfo::loadByTeam($year, $event, $team, $propertyValue) as $robotInfo)
                $robotInfoArray[] = $robotInfo;
        }


        return $robotInfoArray;
    }

    /**
     * Gets all robot info for a specific team at an event, or in a year
     * @param Years | null $year if specified, filters by year
     * @param Events | null $event if specified, filters by event
     * @param Teams | null $team if specified, filters by team
     * @param String $propertyKey name of property to filter by
     * @return RobotInfoArray
     */
    private static function loadByTeam($year = null, $event = null, $team = null, $propertyKey)
    {
        require_once(ROOT_DIR . '/classes/tables/Years.php');
        require_once(ROOT_DIR . '/classes/tables/Events.php');
        require_once(ROOT_DIR . '/classes/tables/Teams.php');

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'PropertyKey';
        $args[] = $propertyKey;

        //if team specified, filter by year
        if(!empty($year))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        //if team specified, filter by event
        if(!empty($event))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'EventId';
            $args[] = $event->BlueAllianceId;
        }

        //if team specified, filter by team
        if(!empty($team))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        $rows = self::query($sql, $cols, $args);

        $robotInfoArray = new RobotInfoArray();

        foreach ($rows as $row)
        {
            $robotInfo = new RobotInfo();
            foreach($row as $key => $value)
            {
                if(property_exists($robotInfo, $key))
                    $robotInfo->$key = $value;

            }

            $robotInfoArray[] = $robotInfo;
        }

        return $robotInfoArray;
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