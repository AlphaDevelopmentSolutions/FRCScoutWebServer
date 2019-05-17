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
    public static function getRobotInfoKeys($year = null, $event = null, $keyState = null)
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

        $rows = self::query($sql, $cols, $args);

        foreach($rows as $row)
            $response[] = RobotInfoKeys::withProperties($row);

        return $response;
    }
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