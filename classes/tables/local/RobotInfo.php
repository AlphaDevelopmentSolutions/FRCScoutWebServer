<?php

class RobotInfo extends LocalTable
{
    public $Id;
    public $YearId;
    public $EventId;
    public $TeamId;
    public $PropertyValue;
    public $PropertyKeyId;

    public static $TABLE_NAME = 'robot_info';

    /**
     * Retrieves objects from the database
     * @param LocalDatabase $database
     * @param null | RobotInfo $robotInfo if specified, filters by id
     * @param Years | null $year if specified, filters by id
     * @param Events | null $event if specified, filters by id
     * @param Teams | null $team if specified, filters by id
     * @param RobotInfoKeys[] | array $robotInfoKeys if specified, filters by robot info keys
     * @return RobotInfo[]
     */
    public static function getObjects($database, $robotInfo = null, $year = null, $event = null, $team = null, $robotInfoKeys = array())
    {
        $whereStatement = "";
        $cols = array();
        $args = array();

        //if scout card info key specified, filter by scout card info key
        if(!empty($robotInfo))
        {
            $whereStatement = "! = ?";
            $cols[] = "Id";
            $args[] = $robotInfo->Id;
        }

        //if year specified, filter by year
        if(!empty($year))
        {
            $whereStatement .= ((empty($whereStatement)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        //if event specified, filter by event
        if(!empty($event))
        {
            $whereStatement .= ((empty($whereStatement)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'EventId';
            $args[] = $event->BlueAllianceId;
        }

        //if team specified, filter by team
        if(!empty($team))
        {
            $whereStatement .= ((empty($whereStatement)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        if(!empty($robotInfoKeys))
        {
            $whereStatement .= ((empty($whereStatement)) ? "" : " AND ") . " ! IN (";
            $cols[] = 'PropertyKeyId';


            $appendString = "";

            foreach($robotInfoKeys as $robotInfoKey)
            {
                if(!empty($appendString))
                    $appendString .= ", ";

                $appendString .= "?";

                $args[] = $robotInfoKey->Id;
            }

            $whereStatement .= $appendString . ")";
        }

        return parent::getObjects($database, $whereStatement, $cols, $args);
    }

    /**
     * Overrides parent save function to overwrite existing records in case of conflicts
     * @param LocalDatabase $database
     * @param CoreDatabase $coreDatabase
     * @return bool
     */
    public function save($database, $coreDatabase)
    {
        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/core/Events.php');
        require_once(ROOT_DIR . '/classes/tables/core/Years.php');

        $robotInfoArray = self::getObjects($database, null, Years::withId($coreDatabase, $this->YearId), Events::withId($coreDatabase, $this->EventId), Teams::withId($coreDatabase, $this->TeamId));

        foreach ($robotInfoArray as $robotInfo)
        {
            if ($robotInfo->PropertyKeyId == $this->PropertyKeyId)
                $this->Id = $robotInfo->Id;
        }

        return parent::save($database);
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