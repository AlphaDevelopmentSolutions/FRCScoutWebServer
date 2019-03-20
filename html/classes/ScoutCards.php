<?php

class ScoutCards
{
    public $Id;
    public $MatchId;
    public $TeamId;
    public $EventId;
    public $AllianceColor;
    public $CompletedBy;
    public $BlueAllianceFinalScore;
    public $RedAllianceFinalScore;
    public $PreGameStartingPiece;
    public $PreGameStartingPosition;
    public $PreGameStartingLevel;
    public $AutonomousExitHabitat;
    public $EndGameReturnedToHabitat;
    public $EndGameReturnedToHabitatAttempts;
    public $Notes;
    public $CompletedDate;

    private static $TABLE_NAME = 'scout_cards';

    function load($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM '.self::$TABLE_NAME.' WHERE '.'id = '.$database->quote($id);
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
                                      MatchId,
                                      TeamId,
                                      EventId,
                                      AllianceColor,
                                      CompletedBy,
                                      BlueAllianceFinalScore,
                                      RedAllianceFinalScore,
                                      PreGameStartingPiece,
                                      PreGameStartingPosition,
                                      PreGameStartingLevel,
                                      AutonomousExitHabitat,
                                      EndGameReturnedToHabitat,
                                      EndGameReturnedToHabitatAttempts,
                                      Notes,
                                      CompletedDate
                                      )
                                      VALUES 
                                      (
                                      ' . ((empty($this->MatchId)) ? 'NULL' : $database->quote($this->MatchId)) .',
                                      ' . ((empty($this->TeamId)) ? 'NULL' : $database->quote($this->TeamId)) .',
                                      ' . ((empty($this->EventId)) ? 'NULL' : $database->quote($this->EventId)) .',
                                      ' . ((empty($this->AllianceColor)) ? 'NULL' : $database->quote($this->AllianceColor)) .',
                                      ' . ((empty($this->CompletedBy)) ? 'NULL' : $database->quote($this->CompletedBy)) .',
                                      
                                      ' . ((empty($this->BlueAllianceFinalScore)) ? '0' : $database->quote($this->BlueAllianceFinalScore)) .',
                                      ' . ((empty($this->RedAllianceFinalScore)) ? '0' : $database->quote($this->RedAllianceFinalScore)) .',
                                      
                                      ' . ((empty($this->PreGameStartingPiece)) ? '0' : $database->quote($this->PreGameStartingPiece)) .',
                                      ' . ((empty($this->PreGameStartingPosition)) ? '0' : $database->quote($this->PreGameStartingPosition)) .',
                                      ' . ((empty($this->PreGameStartingLevel)) ? '0' : $database->quote($this->PreGameStartingLevel)) .',
                                      
                                      ' . ((empty($this->AutonomousExitHabitat)) ? '0' : $database->quote($this->AutonomousExitHabitat)) .',
                                      
                                      ' . ((empty($this->EndGameReturnedToHabitat)) ? 'NULL' : $database->quote($this->EndGameReturnedToHabitat)) .',
                                      ' . ((empty($this->EndGameReturnedToHabitatAttempts)) ? 'NULL' : $database->quote($this->EndGameReturnedToHabitatAttempts)) .',
                                      
                                      ' . ((empty($this->Notes)) ? 'NULL' : $database->quote($this->Notes)) .',
                                      
                                      ' . ((empty($this->CompletedDate)) ? 'NULL' : $database->quote($this->CompletedDate)) .'
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
            MatchId = " . ((empty($this->MatchId)) ? "NULL" : $database->quote($this->MatchId)) .", 
            TeamId = " . ((empty($this->TeamId)) ? "NULL" : $database->quote($this->TeamId)) .", 
            EventId = " . ((empty($this->EventId)) ? "NULL" : $database->quote($this->EventId)) .", 
            AllianceColor = " . ((empty($this->AllianceColor)) ? "NULL" : $database->quote($this->AllianceColor)) .", 
            
            CompletedBy = " . ((empty($this->CompletedBy)) ? "NULL" : $database->quote($this->CompletedBy)) .", 
            BlueAllianceFinalScore = " . ((empty($this->BlueAllianceFinalScore)) ? "NULL" : $database->quote($this->BlueAllianceFinalScore)) .", 
            RedAllianceFinalScore = " . ((empty($this->RedAllianceFinalScore)) ? "NULL" : $database->quote($this->RedAllianceFinalScore)) .", 
            
            PreGameStartingPiece = " . ((empty($this->PreGameStartingPiece)) ? "NULL" : $database->quote($this->PreGameStartingPiece)) .", 
            PreGameStartingPosition = " . ((empty($this->PreGameStartingPosition)) ? "NULL" : $database->quote($this->PreGameStartingPosition)) .", 
            PreGameStartingLevel = " . ((empty($this->PreGameStartingLevel)) ? "NULL" : $database->quote($this->PreGameStartingLevel)) .", 
            
            AutonomousExitHabitat = " . ((empty($this->AutonomousExitHabitat)) ? "NULL" : $database->quote($this->AutonomousExitHabitat)) .", 
           
            EndGameReturnedToHabitat = " . ((empty($this->EndGameReturnedToHabitat)) ? "NULL" : $database->quote($this->EndGameReturnedToHabitat)) .", 
            EndGameReturnedToHabitatAttempts = " . ((empty($this->EndGameReturnedToHabitatAttempts)) ? "NULL" : $database->quote($this->EndGameReturnedToHabitatAttempts)) .", 
           
            Notes = " . ((empty($this->Notes)) ? "NULL" : $database->quote($this->Notes)) .", 
            CompletedDate = " . ((empty($this->CompletedDate)) ? "NULL" : $database->quote($this->CompletedDate)) ."
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

    public static function getScoutCardsForTeam($teamId, $eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME ." 
                    WHERE 
                      TeamId = " . $database->quote($teamId) .
                    'AND
                        EventId = ' . $database->quote($eventId) .
                    'ORDER BY MatchId DESC'
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

    public static function getScoutCardsForEvent($eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME ."  
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