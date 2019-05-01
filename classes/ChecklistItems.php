<?php

class ChecklistItems
{
    public $Id;
    public $Title;
    public $Description;

    private static $TABLE_NAME = 'checklist_items';

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return ChecklistItems
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
     * @return ChecklistItems
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
     */
    protected function loadByProperties(Array $properties = array())
    {
        foreach($properties as $key => $value)
            $this->{$key} = $value;

    }

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return boolean
     */
    protected function loadById($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM ' . self::$TABLE_NAME . ' WHERE '.'Id = '.$database->quote($id);
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

        if(empty($this->Id))
        {
            $sql = 'INSERT INTO ' . self::$TABLE_NAME . ' 
                                      (
                                        Title,
                                        Description
                                      )
                                      VALUES 
                                      (
                                      ' . ((empty($this->Title)) ? 'NULL' : $database->quote($this->Title)) .',                                      
                                      ' . ((empty($this->Description)) ? 'NULL' : $database->quote($this->Description)) .'
                                      );';

            if($database->query($sql))
            {
                $this->Id = $database->lastInsertedID();
                $database->close();

                return true;
            }
            $database->close();
            return false;

        }
        else
        {
            $sql = "UPDATE " . self::$TABLE_NAME . " SET 
            Title = " . ((empty($this->Title)) ? "NULL" : $database->quote($this->Title)) .",             
            Description = " . ((empty($this->Description)) ? "NULL" : $database->quote($this->Description)) ."
            WHERE (Id = " . $database->quote($this->Id) . ");";

            if($database->query($sql))
            {
                $database->close();
                return true;
            }

            $database->close();
            return false;
        }
    }

    /**
     * Gets all created checklist items
     * @return array
     */
    public static function getChecklistItems()
    {
        $database = new Database();
        $checklistItems = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME);
        $database->close();

        $response = array();

        if($checklistItems && $checklistItems->num_rows > 0)
        {
            while ($row = $checklistItems->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

}

?>