<?php

class RobotInfoKeys extends LocalTable
{
    public $Id;
    public $YearId;
    public $KeyState;
    public $KeyName;
    public $SortOrder;

    public static $TABLE_NAME = 'robot_info_keys';

    /**
     * Retrieves objects from the database
     * @param Years | null $year if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return RobotInfoKeys[]
     */
    public static function getObjects($year = null, $orderBy = 'SortOrder', $orderDirection = 'ASC')
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

        return parent::getObjects($whereStatment, $cols, $args, $orderBy, $orderDirection);
    }
    
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
     * Override for the Table class delete function
     * Ensures all records associated with this key are deleted before deletion
     * @return bool
     */
    public function delete()
    {
        if(!empty($this->Id))
        {
            require_once(ROOT_DIR . '/classes/tables/local/RobotInfo.php');

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

    public function toString()
    {
        return $this->KeyName;
    }

    public function toHtml()
    {
        return '';
    }
}