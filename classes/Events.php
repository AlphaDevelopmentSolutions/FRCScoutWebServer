<?php

class Events
{
    public $Id;
    public $BlueAllianceId;
    public $Name;
    public $City;
    public $StateProvince;
    public $Country;
    public $StartDate;
    public $EndDate;

    private static $TABLE_NAME = 'events';

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return new instance
     */
    static function withId($id)
    {
        $instance = new self();
        $instance->loadById($id);
        return $instance;

    }

    /**
     * Loads a new instance by specified properties
     * @param array $properties
     * @return new instance
     */
    static function withProperties(Array $properties = array())
    {
        $instance = new self();
        $instance->loadByProperties($properties);
        return $instance;

    }

    /**
     * Loads a new instance by specified properties
     * @param array $properties
     * @return new instance
     */
    protected function loadByProperties(Array $properties = array())
    {
        foreach($properties as $key => $value)
            $this->{$key} = $value;

    }

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return new instance
     */
    protected function loadById($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM ' . self::$TABLE_NAME . ' WHERE '.'BlueAllianceId = '.$database->quote($id);
        $rs = $database->query($sql);

        if($rs && $rs->num_rows > 0) {
            $row = $rs->fetch_assoc();

            if(is_array($row)) {
                foreach($row as $key => $value){
                    if(property_exists($this, $key)){
                        $this->$key = $value;
                    }
                }
            }

            return true;
        }

        return false;
    }

    function save()
    {
        $database = new Database();
        $sql = 'INSERT INTO ' . Events::$TABLE_NAME . ' 
                                  (
                                  BlueAllianceId,
                                  Name,
                                  City,
                                  StateProvince,
                                  Country,
                                  StartDate,
                                  EndDate
                                  )
                                  VALUES 
                                  (
                                  ' . ((empty($this->BlueAllianceId)) ? 'NULL' : $database->quote($this->BlueAllianceId)) .',
                                  ' . ((empty($this->Name)) ? 'NULL' : $database->quote($this->Name)) .',
                                  ' . ((empty($this->City)) ? 'NULL' : $database->quote($this->City)) .',
                                  ' . ((empty($this->StateProvince)) ? 'NULL' : $database->quote($this->StateProvince)) .',
                                  ' . ((empty($this->Country)) ? 'NULL' : $database->quote($this->Country)) .',
                                  ' . ((empty($this->StartDate)) ? '2019-01-01 00:00:00' : $database->quote($this->StartDate)) .',
                                  ' . ((empty($this->EndDate)) ? '2019-01-01 00:00:00' : $database->quote($this->EndDate)) .'
                                  );';
        if($database->query($sql))
        {
            $database->close();

            return true;
        }
        $database->close();
        return false;
    }

    public static function getEvents()
    {
        $database = new Database();
        $events = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME .
                    " ORDER BY StartDate DESC "
        );
        $database->close();

        $response = array();

        if($events && $events->num_rows > 0)
        {
            while ($row = $events->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

}

?>