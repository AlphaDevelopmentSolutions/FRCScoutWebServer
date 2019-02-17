<?php

class PitCards
{
    public $Id;
    public $TeamId;
    public $EventId;
    public $DriveStyle;
    public $AutoExitHabitat;
    public $AutoHatch;
    public $AutoCargo;
    public $TeleopHatch;
    public $TeleopCargo;
    public $TeleopRocketsComplete;
    public $ReturnToHabitat;
    public $Notes;
    public $CompletedBy;

    private static $TABLE_NAME = 'pit_cards';

    function load($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM '. PitCards::$TABLE_NAME.' WHERE '.'id = '.$database->quote($id);
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
                        EventId = ' . $database->quote($eventId)
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


}

?>