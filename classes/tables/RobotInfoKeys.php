<?php

class RobotInfoKeys extends Table
{
    public $Id;
    public $YearId;
    public $KeyState;
    public $KeyName;
    public $SortOrder;

    public static $TABLE_NAME = 'robot_info_keys';
    
    /**
     * Gets and returns all keys from the database
     * @param Years | null $year if specified, filters keys by year
     * @param Events | null $event if specified, filters keys by event
     * @param string | null $keyState if specified, filters keys by state
     * @return RobotInfoKeys[]
     */
    public static function getKeys($year = null, $event = null, $keyState = null)
    {
        $yearId = ((!empty($year)) ? $year->Id : ((!empty($event)) ? $event->YearId : date('Y')));

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'YearId';
        $args[] = $yearId;

        if(!empty($keyState))
        {
            $sql .= " AND ! = ? ";
            $cols[] = 'KeyState';
            $args[] = $keyState;
        }

        $sql .= " ORDER BY ! ASC";
        $cols[] = 'SortOrder';

        $rows = self::queryRecords($sql, $cols, $args);

        foreach($rows as $row)
            $response[] = RobotInfoKeys::withProperties($row);

        return $response;
    }

    /**
     * Override for the Table class save function
     * Ensures all records associated with this key are updated before saving
     * @return bool
     */
    public function save()
    {
        if(!empty($this->Id))
        {
            require_once(ROOT_DIR . '/classes/tables/RobotInfo.php');

            $currRobotInfoKey = RobotInfoKeys::withId($this->Id);

            //create the sql statement
            $sql = "UPDATE ! SET ! = ? WHERE ! = ? AND ! = ?";
            $cols[] = RobotInfo::$TABLE_NAME;

            //Set
            $cols[] = 'PropertyKeyId';
            $args[] = $this->PropertyKeyId;

            //Where
            $cols[] = 'YearId';
            $args[] = $currRobotInfoKey->YearId;

            $cols[] = 'PropertyKeyId';
            $args[] = $currRobotInfoKey->PropertyKeyId;

            self::insertOrUpdateRecords($sql, $cols, $args);
        }

        return parent::save();
    }

    /**
     * Override for the Table class delete function
     * Ensures all records associated with this key are deleted before deletion
     * @return bool
     */
    public function delete()
    {
        if(!empty($this->Id))
        {
            require_once(ROOT_DIR . '/classes/tables/RobotInfo.php');

            //create the sql statement
            $sql = "DELETE FROM ! WHERE ! = ? AND ! = ?";
            $cols[] = RobotInfo::$TABLE_NAME;

            //Where
            $cols[] = 'YearId';
            $args[] = $this->YearId;

            $cols[] = 'PropertyKeyId';
            $args[] = $this->PropertyKeyId;

            self::deleteRecords($sql, $cols, $args);
        }

        return parent::delete();
    }

    /**
     * Override for the Table class getObjects method
     * @return RobotInfoKeys[]
     */
    public static function getObjects()
    {
        return parent::getObjects('SortOrder', 'ASC');
    }

    public function toString()
    {
        return $this->KeyName;
    }

    public function toHtml()
    {
        return '';
    }
}