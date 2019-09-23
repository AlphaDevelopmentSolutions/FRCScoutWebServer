<?php

class ScoutCardInfoKeys extends LocalTable
{
    public $Id;
    public $YearId;

    public $KeyState;
    public $KeyName;

    public $SortOrder;

    public $MinValue;
    public $MaxValue;

    public $NullZeros;
    public $IncludeInStats;

    public $DataType;

    public static $TABLE_NAME = 'scout_card_info_keys';
    
    /**
     * Gets and returns all keys from the database
     * @param Years | null $year if specified, filters keys by year
     * @param Events | null $event if specified, filters keys by event
     * @param string | null $keyState if specified, filters keys by state
     * @return ScoutCardInfoKeys[]
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
            $response[] = self::withProperties($row);

        return $response;
    }

    /**
     * @return static
     */
    public static function withStateAndName($keyState, $keyName)
    {
        $instance = new self();
        $instance->loadByStateAndKey($keyState, $keyName);
        return $instance;
    }

    /**
     * Loads a new instance by its database id
     * @return boolean
     */
    protected function loadByStateAndKey($keyState, $keyName)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ?";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'KeyState';
        $args[] = $keyState;
        $cols[] = 'KeyName';
        $args[] = $keyName;

        $rows = self::queryRecords($sql, $cols, $args);

        foreach ($rows as $row)
        {
            foreach($row as $key => $value)
            {
                if(property_exists($this, $key))
                    $this->$key = $value;

            }

            return true;
        }

        return false;
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
            require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');

            $currScoutCardInfokey = RobotInfoKeys::withId($this->Id);

            //create the sql statement
            $sql = "UPDATE ! SET ! = ? WHERE ! = ? AND ! = ?";
            $cols[] = ScoutCardInfo::$TABLE_NAME;

            //Set
            $cols[] = 'PropertyKeyId';
            $args[] = $this->Id;

            //Where
            $cols[] = 'YearId';
            $args[] = $currScoutCardInfokey->YearId;

            $cols[] = 'PropertyKeyId';
            $args[] = $currScoutCardInfokey->Id;

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
            require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');

            //create the sql statement
            $sql = "DELETE FROM ! WHERE ! = ? AND ! = ?";
            $cols[] = ScoutCardInfo::$TABLE_NAME;

            //Where
            $cols[] = 'YearId';
            $args[] = $this->YearId;

            $cols[] = 'PropertyKeyId';
            $args[] = $this->Id;

            self::deleteRecords($sql, $cols, $args);
        }

        return parent::delete();
    }

    /**
     * Override for the Table class getObjects method
     * @param string $orderBy column to order objects by
     * @param string $orderDirection direction to order objects by
     * @return ScoutCardInfoKeys[]
     */
    public static function getObjects($orderBy = 'SortOrder', $orderDirection = 'ASC')
    {
        return parent::getObjects($orderBy, $orderDirection);
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

interface DataTypes
{
    const INT = 'INT';
    const BOOL = 'BOOL';
    const TEXT = 'TEXT';
}