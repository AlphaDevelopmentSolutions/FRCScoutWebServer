<?php

class PitCards
{
    public $Id;
    public $TeamId;
    public $EventId;

    public $DriveStyle;
    public $RobotWeight;
    public $RobotLength;
    public $RobotWidth;
    public $RobotHeight;

    public $AutoExitHabitat;
    public $AutoHatch;
    public $AutoCargo;

    public $TeleopHatch;
    public $TeleopCargo;

    public $ReturnToHabitat;
    public $Notes;

    public $CompletedBy;

    private static $TABLE_NAME = 'pit_cards';

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
        $sql = 'SELECT * FROM ' . self::$TABLE_NAME . ' WHERE '.'id = '.$database->quote($id);
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
            $sql = 'INSERT INTO ' . PitCards::$TABLE_NAME . ' 
                                      (
                                      TeamId,
                                      EventId,
                                      
                                      DriveStyle,
                                      RobotWeight,
                                      RobotLength,
                                      RobotWidth,
                                      RobotHeight,
                                      
                                      AutoExitHabitat,
                                      AutoHatch,
                                      AutoCargo,
                                      
                                      TeleopHatch,
                                      TeleopCargo,
                                      
                                      ReturnToHabitat,
                                      
                                      Notes,
                                      
                                      CompletedBy
                                      )
                                      VALUES 
                                      (
                                      ' . ((empty($this->TeamId)) ? '0' : $database->quote($this->TeamId)) .',
                                      ' . ((empty($this->EventId)) ? 'NULL' : $database->quote($this->EventId)) .',
                                      
                                      ' . ((empty($this->DriveStyle)) ? 'NULL' : $database->quote($this->DriveStyle)) .',
                                      ' . ((empty($this->RobotWeight)) ? 'NULL' : $database->quote($this->RobotWeight)) .',
                                      ' . ((empty($this->RobotLength)) ? 'NULL' : $database->quote($this->RobotLength)) .',
                                      ' . ((empty($this->RobotWidth)) ? 'NULL' : $database->quote($this->RobotWidth)) .',
                                      ' . ((empty($this->RobotHeight)) ? 'NULL' : $database->quote($this->RobotHeight)) .',
                                      
                                      ' . ((empty($this->AutoExitHabitat)) ? 'NULL' : $database->quote($this->AutoExitHabitat)) .',
                                      ' . ((empty($this->AutoHatch)) ? 'NULL' : $database->quote($this->AutoHatch)) .',
                                      ' . ((empty($this->AutoCargo)) ? 'NULL' : $database->quote($this->AutoCargo)) .',
                                      
                                      ' . ((empty($this->TeleopHatch)) ? 'NULL' : $database->quote($this->TeleopHatch)) .',
                                      ' . ((empty($this->TeleopCargo)) ? 'NULL' : $database->quote($this->TeleopCargo)) .',
                                      
                                      ' . ((empty($this->ReturnToHabitat)) ? 'NULL' : $database->quote($this->ReturnToHabitat)) .',
                                      
                                      ' . ((empty($this->Notes)) ? 'NULL' : $database->quote($this->Notes)) .',
                                      
                                      ' . ((empty($this->CompletedBy)) ? 'NULL' : $database->quote($this->CompletedBy)) .'
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
            $sql = "UPDATE " . PitCards::$TABLE_NAME . " SET 
            TeamId = " . ((empty($this->TeamId)) ? "0" : $database->quote($this->TeamId)) .", 
            EventId = " . ((empty($this->EventId)) ? "NULL" : $database->quote($this->EventId)) .", 
            
            DriveStyle = " . ((empty($this->DriveStyle)) ? "NULL" : $database->quote($this->DriveStyle)) .", 
            RobotWeight = " . ((empty($this->RobotWeight)) ? "NULL" : $database->quote($this->RobotWeight)) .", 
            RobotLength = " . ((empty($this->RobotLength)) ? "NULL" : $database->quote($this->RobotLength)) .", 
            RobotWidth = " . ((empty($this->RobotWidth)) ? "NULL" : $database->quote($this->RobotWidth)) .", 
            RobotHeight = " . ((empty($this->RobotHeight)) ? "NULL" : $database->quote($this->RobotHeight)) .", 
            
            AutoExitHabitat = " . ((empty($this->AutoExitHabitat)) ? "NULL" : $database->quote($this->AutoExitHabitat)) .", 
            AutoHatch = " . ((empty($this->AutoHatch)) ? "NULL" : $database->quote($this->AutoHatch)) .", 
            AutoCargo = " . ((empty($this->AutoCargo)) ? "NULL" : $database->quote($this->AutoCargo)) .", 
            
            TeleopHatch = " . ((empty($this->TeleopHatch)) ? "NULL" : $database->quote($this->TeleopHatch)) .", 
            TeleopCargo = " . ((empty($this->TeleopCargo)) ? "NULL" : $database->quote($this->TeleopCargo)) .", 
            
            ReturnToHabitat = " . ((empty($this->ReturnToHabitat)) ? "NULL" : $database->quote($this->ReturnToHabitat)) .", 
            
            Notes = " . ((empty($this->Notes)) ? "NULL" : $database->quote($this->Notes)) .", 
            
            CompletedBy = " . ((empty($this->CompletedBy)) ? "NULL" : $database->quote($this->CompletedBy)) ."
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

    function delete()
    {
        if(empty($this->Id))
            return false;

        $database = new Database();
        $sql = 'DELETE FROM '.self::$TABLE_NAME.' WHERE '.'id = '.$database->quote($this->Id);
        $rs = $database->query($sql);

        if($rs)
            return true;


        return false;
    }

    public static function getPitCardsForTeam($teamId, $eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . PitCards::$TABLE_NAME . " 
                    WHERE 
                      TeamId = " . $database->quote($teamId) .
                    'AND
                        EventId = ' . $database->quote($eventId) .
                    'ORDER BY Id DESC'
        );
        $database->close();

        $response = array();

        if($scoutCards && $scoutCards->num_rows > 0)
        {
            while ($row = $scoutCards->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public static function getNewestPitCard($teamId, $eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . PitCards::$TABLE_NAME . " 
                    WHERE 
                      TeamId = " . $database->quote($teamId) .
                    'AND
                        EventId = ' . $database->quote($eventId) .
                    'ORDER BY Id DESC LIMIT 1'
        );
        $database->close();

        $response = array();

        if($scoutCards && $scoutCards->num_rows > 0)
        {
            while ($row = $scoutCards->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public static function getPitCardsForEvent($eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . PitCards::$TABLE_NAME . " 
                    WHERE 
                        EventId = " . $database->quote($eventId)
        );
        $database->close();

        $response = array();

        if($scoutCards && $scoutCards->num_rows > 0)
        {
            while ($row = $scoutCards->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }


}

?>