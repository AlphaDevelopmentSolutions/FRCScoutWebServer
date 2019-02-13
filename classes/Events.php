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

    function load($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM '.$this::$TABLE_NAME.' WHERE '.'BlueAllianceId = '.$database->quote($id);
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
                                  ' . ((empty($this->StartDate)) ? 'NULL' : $database->quote($this->StartDate)) .',
                                  ' . ((empty($this->EndDate)) ? 'NULL' : $database->quote($this->EndDate)) .'
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
                      " . Events::$TABLE_NAME
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