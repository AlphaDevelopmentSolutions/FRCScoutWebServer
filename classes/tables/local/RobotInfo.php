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
     * Gets all robot info for a specific team at an event, or in a year
     * @param Years | null $year if specified, filters by year
     * @param Events | null $event if specified, filters by event
     * @param Teams | null $team if specified, filters by team
     * @return RobotInfoArray
     */
    public static function forTeam($year = null, $event = null, $team = null)
    {
        require_once(ROOT_DIR . '/classes/tables/local/RobotInfoKeys.php');
        require_once(ROOT_DIR . '/classes/tables/local/RobotInfoArray.php');

        $robotInfoArray = new RobotInfoArray();

        foreach(RobotInfoKeys::getKeys($year, $event) as $robotInfoKey)
        {
            foreach(self::loadByTeam($year, $event, $team, $robotInfoKey) as $robotInfo)
                $robotInfoArray[] = $robotInfo;
        }


        return $robotInfoArray;
    }

    /**
     * Gets all robot info for a specific team at an event, or in a year
     * @param Years | null $year if specified, filters by year
     * @param Events | null $event if specified, filters by event
     * @param Teams | null $team if specified, filters by team
     * @param RobotInfoKeys $robotInfoKey robot info key to load
     * @return RobotInfoArray
     */
    private static function loadByTeam($year = null, $event = null, $team = null, $robotInfoKey)
    {

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'PropertyKeyId';
        $args[] = $robotInfoKey->Id;

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

        //if team specified, filter by team
        if(!empty($team))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        $rows = self::queryRecords($sql, $cols, $args);

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
     * Overrides parent save function
     * Updates or inserts record into database
     * @return bool
     */
    public function save()
    {
        if(!empty($this->PropertyValue))
        {
            require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
            require_once(ROOT_DIR . '/classes/tables/core/Events.php');
            require_once(ROOT_DIR . '/classes/tables/core/Years.php');

            $robotInfoArray = self::forTeam(Years::withId($this->YearId), Events::withId($this->EventId), Teams::withId($this->TeamId));

            $updateRecord = false;

            foreach ($robotInfoArray as $robotInfo)
            {
                if ($robotInfo->YearId == $this->YearId &&
                    $robotInfo->EventId == $this->EventId &&
                    $robotInfo->TeamId == $this->TeamId &&
                    $robotInfo->PropertyKeyId == $this->PropertyKeyId)
                    $updateRecord = true;
            }

            if (!$updateRecord)
            {
                //create the sql statement
                $sql = "INSERT INTO ! (";
                $cols[] = $this::$TABLE_NAME;

                $columnsString = '';
                $valuesString = '';
                //iterate through each field in the current class
                foreach ($this as $key => $value)
                {
                    //dont use Id in cols or vals
                    if ($key != 'Id' && property_exists($this, $key))
                    {
                        //only add to insert statement if value is not empty
                        if (!empty($value) || $value == '0')
                        {
                            if (!empty($columnsString))
                                $columnsString .= ', ';

                            $columnsString .= '!';
                            $cols[] = $key;

                            if (!empty($valuesString))
                                $valuesString .= ', ';

                            $valuesString .= '?';
                            $args[] = $value;
                        }
                    }
                }

                $sql .= "$columnsString) VALUES ($valuesString)";

                if ($insertId = self::insertOrUpdateRecords($sql, $cols, $args) > -1)
                {
                    $this->Id = $insertId;

                    return true;
                }
                return false;

            } else
            {
                //create the sql statement
                $sql = "UPDATE ! SET ";
                $cols[] = $this::$TABLE_NAME;

                $updates = '';
                //iterate through each field in the current class
                foreach ($this as $key => $value)
                {
                    //dont use Id in cols or vals
                    if ($key != 'Id' && property_exists($this, $key))
                    {
                        //only add to insert statement if value is not empty
                        if (!empty($value) || $value == '0')
                        {
                            if (!empty($updates))
                                $updates .= ', ';

                            $updates .= ' ! = ?';
                            $cols[] = $key;
                            $args[] = $value;
                        }
                    }
                }

                $sql .= $updates . " WHERE ! = ? AND ! = ? AND ! = ? AND ! = ? ";
                $cols[] = 'YearId';
                $args[] = $this->YearId;
                $cols[] = 'EventId';
                $args[] = $this->EventId;
                $cols[] = 'TeamId';
                $args[] = $this->TeamId;
                $cols[] = 'PropertyKeyId';
                $args[] = $this->PropertyKeyId;

                if ($insertId = self::insertOrUpdateRecords($sql, $cols, $args) > -1)
                    return true;

                return false;
            }
        }

        return true;
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