<?php

class ChecklistItemResults
{
    public $Id;
    public $ChecklistItemId;
    public $MatchId;
    public $Status;
    public $CompletedBy;
    public $CompletedDate;

    private static $TABLE_NAME = 'checklist_item_results';

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return ChecklistItemResults
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
     * @return ChecklistItemResults
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
                                        ChecklistItemId,
                                        MatchId,
                                        
                                        Status,
                                        CompletedBy,
                                        
                                        CompletedDate
                                      )
                                      VALUES 
                                      (
                                      ' . ((empty($this->ChecklistItemId)) ? '0' : $database->quote($this->ChecklistItemId)) .',                                      
                                      ' . ((empty($this->MatchId)) ? 'NULL' : $database->quote($this->MatchId)) .',          
                                                                  
                                      ' . ((empty($this->Status)) ? 'NULL' : $database->quote($this->Status)) .',                                      
                                      ' . ((empty($this->CompletedBy)) ? 'NULL' : $database->quote($this->CompletedBy)) .',                                      
                                      
                                      ' . ((empty($this->CompletedDate)) ? '2019-01-01 00:00:00' : $database->quote($this->CompletedDate)) .'
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
            ChecklistItemId = " . ((empty($this->ChecklistItemId)) ? "0" : $database->quote($this->ChecklistItemId)) .",             
            MatchId = " . ((empty($this->MatchId)) ? "NULL" : $database->quote($this->MatchId)) .",             
            
            Status = " . ((empty($this->Status)) ? "NULL" : $database->quote($this->Status)) .",             
            CompletedBy = " . ((empty($this->CompletedBy)) ? "NULL" : $database->quote($this->CompletedBy)) .",             
            
            CompletedDate = " . ((empty($this->CompletedDate)) ? "2019-01-01 00:00:00" : $database->quote($this->CompletedDate)) ."             
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
    public static function getChecklistItemResults()
    {
        $database = new Database();
        $checklistItemResults = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME);
        $database->close();

        $response = array();

        if($checklistItemResults && $checklistItemResults->num_rows > 0)
        {
            while ($row = $checklistItemResults->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

}

?>