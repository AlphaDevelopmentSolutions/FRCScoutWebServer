<?php

class ScoutCardInfoKeys extends Table
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

        $rows = self::query($sql, $cols, $args);

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

        $rows = self::query($sql, $cols, $args);

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
     * @return static[]
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

interface DataTypes
{
    const INT = 'INT';
    const BOOL = 'BOOL';
    const TEXT = 'TEXT';
}