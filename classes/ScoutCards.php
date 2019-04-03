<?php

class ScoutCards
{
    public $Id;
    public $MatchId;
    public $TeamId;
    public $EventId;
    public $AllianceColor;
    public $CompletedBy;

    public $PreGameStartingPosition;
    public $PreGameStartingLevel;
    public $PreGameStartingPiece;

    public $AutonomousExitHabitat;
    public $AutonomousHatchPanelsPickedUp;
    public $AutonomousHatchPanelsSecuredAttempts;
    public $AutonomousHatchPanelsSecured;
    public $AutonomousCargoPickedUp;
    public $AutonomousCargoStoredAttempts;
    public $AutonomousCargoStored;

    public $TeleopHatchPanelsPickedUp;
    public $TeleopHatchPanelsSecuredAttempts;
    public $TeleopHatchPanelsSecured;
    public $TeleopCargoPickedUp;
    public $TeleopCargoStoredAttempts;
    public $TeleopCargoStored;

    public $EndGameReturnedToHabitat;
    public $EndGameReturnedToHabitatAttempts;

    public $BlueAllianceFinalScore;
    public $RedAllianceFinalScore;
    public $DefenseRating;
    public $OffenseRating;
    public $DriveRating;
    public $Notes;
    public $CompletedDate;

    private static $TABLE_NAME = 'scout_cards';

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return ScoutCards
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
     * @return ScoutCards
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
     * @return ScoutCards
     */
    protected function loadByProperties(Array $properties = array())
    {
        foreach($properties as $key => $value)
            $this->{$key} = $value;

    }

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return ScoutCards
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
            $sql = 'INSERT INTO ' . self::$TABLE_NAME . ' 
                                      (
                                        MatchId,
                                        TeamId,
                                        EventId,
                                        AllianceColor,
                                        CompletedBy,
                                    
                                        PreGameStartingLevel,
                                        PreGameStartingPosition,
                                        PreGameStartingPiece,
                                    
                                        AutonomousExitHabitat,
                                        AutonomousHatchPanelsPickedUp,
                                        AutonomousHatchPanelsSecuredAttempts,
                                        AutonomousHatchPanelsSecured,
                                        AutonomousCargoPickedUp,
                                        AutonomousCargoStoredAttempts,
                                        AutonomousCargoStored,
                                    
                                        TeleopHatchPanelsPickedUp,
                                        TeleopHatchPanelsSecuredAttempts,
                                        TeleopHatchPanelsSecured,
                                        TeleopCargoPickedUp,
                                        TeleopCargoStoredAttempts,
                                        TeleopCargoStored,
                                    
                                        EndGameReturnedToHabitat,
                                        EndGameReturnedToHabitatAttempts,
                                    
                                        BlueAllianceFinalScore,
                                        RedAllianceFinalScore,
                                        DefenseRating,
                                        OffenseRating,
                                        DriveRating,
                                        Notes,
                                        CompletedDate
                                      )
                                      VALUES 
                                      (
                                      ' . ((empty($this->MatchId)) ? '0' : $database->quote($this->MatchId)) .',
                                      ' . ((empty($this->TeamId)) ? '0' : $database->quote($this->TeamId)) .',
                                      ' . ((empty($this->EventId)) ? 'NULL' : $database->quote($this->EventId)) .',
                                      ' . ((empty($this->AllianceColor)) ? 'NULL' : $database->quote($this->AllianceColor)) .',
                                      ' . ((empty($this->CompletedBy)) ? 'NULL' : $database->quote($this->CompletedBy)) .',
                                    
                                      ' . ((empty($this->PreGameStartingLevel)) ? '0' : $database->quote($this->PreGameStartingLevel)) .',
                                      ' . ((empty($this->PreGameStartingPosition)) ? 'NULL' : $database->quote($this->PreGameStartingPosition)) .',
                                      ' . ((empty($this->PreGameStartingPiece)) ? 'NULL' : $database->quote($this->PreGameStartingPiece)) .',
                                      
                                      ' . ((empty($this->AutonomousExitHabitat)) ? '0' : $database->quote($this->AutonomousExitHabitat)) .',
                                      ' . ((empty($this->AutonomousHatchPanelsPickedUp)) ? '0' : $database->quote($this->AutonomousHatchPanelsPickedUp)) .',
                                      ' . ((empty($this->AutonomousHatchPanelsSecuredAttempts)) ? '0' : $database->quote($this->AutonomousHatchPanelsSecuredAttempts)) .',
                                      ' . ((empty($this->AutonomousHatchPanelsSecured)) ? '0' : $database->quote($this->AutonomousHatchPanelsSecured)) .',
                                      ' . ((empty($this->AutonomousCargoPickedUp)) ? '0' : $database->quote($this->AutonomousCargoPickedUp)) .',
                                      ' . ((empty($this->AutonomousCargoStoredAttempts)) ? '0' : $database->quote($this->AutonomousCargoStoredAttempts)) .',
                                      ' . ((empty($this->AutonomousCargoStored)) ? '0' : $database->quote($this->AutonomousCargoStored)) .',
                                      
                                      ' . ((empty($this->TeleopHatchPanelsPickedUp)) ? '0' : $database->quote($this->TeleopHatchPanelsPickedUp)) .',
                                      ' . ((empty($this->TeleopHatchPanelsSecuredAttempts)) ? '0' : $database->quote($this->TeleopHatchPanelsSecuredAttempts)) .',
                                      ' . ((empty($this->TeleopHatchPanelsSecured)) ? '0' : $database->quote($this->TeleopHatchPanelsSecured)) .',
                                      ' . ((empty($this->TeleopCargoPickedUp)) ? '0' : $database->quote($this->TeleopCargoPickedUp)) .',
                                      ' . ((empty($this->TeleopCargoStoredAttempts)) ? '0' : $database->quote($this->TeleopCargoStoredAttempts)) .',
                                      ' . ((empty($this->TeleopCargoStored)) ? '0' : $database->quote($this->TeleopCargoStored)) .',
                                      
                                      ' . ((empty($this->EndGameReturnedToHabitat)) ? '0' : $database->quote($this->EndGameReturnedToHabitat)) .',
                                      ' . ((empty($this->EndGameReturnedToHabitatAttempts)) ? '0' : $database->quote($this->EndGameReturnedToHabitatAttempts)) .',
                                      
                                      ' . ((empty($this->BlueAllianceFinalScore)) ? '0' : $database->quote($this->BlueAllianceFinalScore)) .',
                                      ' . ((empty($this->RedAllianceFinalScore)) ? '0' : $database->quote($this->RedAllianceFinalScore)) .',
                                      ' . ((empty($this->DefenseRating)) ? '0' : $database->quote($this->DefenseRating)) .',
                                      ' . ((empty($this->OffenseRating)) ? '0' : $database->quote($this->OffenseRating)) .',
                                      ' . ((empty($this->DriveRating)) ? '0' : $database->quote($this->DriveRating)) .',
                                      ' . ((empty($this->Notes)) ? 'NULL' : $database->quote($this->Notes)) .',
                                      
                                      ' . ((empty($this->CompletedDate)) ? '0' : $database->quote($this->CompletedDate)) .'
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
            MatchId = " . ((empty($this->MatchId)) ? "0" : $database->quote($this->MatchId)) .", 
            TeamId = " . ((empty($this->TeamId)) ? "0" : $database->quote($this->TeamId)) .", 
            EventId = " . ((empty($this->EventId)) ? "NULL" : $database->quote($this->EventId)) .", 
            AllianceColor = " . ((empty($this->AllianceColor)) ? "NULL" : $database->quote($this->AllianceColor)) .", 
            CompletedBy = " . ((empty($this->CompletedBy)) ? "NULL" : $database->quote($this->CompletedBy)) .", 

            PreGameStartingLevel = " . ((empty($this->PreGameStartingLevel)) ? "0" : $database->quote($this->PreGameStartingLevel)) .", 
            PreGameStartingPosition = " . ((empty($this->PreGameStartingPosition)) ? "NULL" : $database->quote($this->PreGameStartingPosition)) .", 
            PreGameStartingPiece = " . ((empty($this->PreGameStartingPiece)) ? "NULL" : $database->quote($this->PreGameStartingPiece)) .", 
            
            AutonomousExitHabitat = " . ((empty($this->AutonomousExitHabitat)) ? "0" : $database->quote($this->AutonomousExitHabitat)) .", 
            AutonomousHatchPanelsPickedUp = " . ((empty($this->AutonomousHatchPanelsPickedUp)) ? "0" : $database->quote($this->AutonomousHatchPanelsPickedUp)) .", 
            AutonomousHatchPanelsSecuredAttempts = " . ((empty($this->AutonomousHatchPanelsSecuredAttempts)) ? "0" : $database->quote($this->AutonomousHatchPanelsSecuredAttempts)) .", 
            AutonomousHatchPanelsSecured = " . ((empty($this->AutonomousHatchPanelsSecured)) ? "0" : $database->quote($this->AutonomousHatchPanelsSecured)) .", 
            AutonomousCargoPickedUp = " . ((empty($this->AutonomousCargoPickedUp)) ? "0" : $database->quote($this->AutonomousCargoPickedUp)) .", 
            AutonomousCargoStoredAttempts = " . ((empty($this->AutonomousCargoStoredAttempts)) ? "0" : $database->quote($this->AutonomousCargoStoredAttempts)) .", 
            AutonomousCargoStored = " . ((empty($this->AutonomousCargoStored)) ? "0" : $database->quote($this->AutonomousCargoStored)) .", 
            
            TeleopHatchPanelsPickedUp = " . ((empty($this->TeleopHatchPanelsPickedUp)) ? "0" : $database->quote($this->TeleopHatchPanelsPickedUp)) .", 
            TeleopHatchPanelsSecuredAttempts = " . ((empty($this->TeleopHatchPanelsSecuredAttempts)) ? "0" : $database->quote($this->TeleopHatchPanelsSecuredAttempts)) .", 
            TeleopHatchPanelsSecured = " . ((empty($this->TeleopHatchPanelsSecured)) ? "0" : $database->quote($this->TeleopHatchPanelsSecured)) .", 
            TeleopCargoPickedUp = " . ((empty($this->TeleopCargoPickedUp)) ? "0" : $database->quote($this->TeleopCargoPickedUp)) .", 
            TeleopCargoStoredAttempts = " . ((empty($this->TeleopCargoStoredAttempts)) ? "0" : $database->quote($this->TeleopCargoStoredAttempts)) .", 
            TeleopCargoStored = " . ((empty($this->TeleopCargoStored)) ? "0" : $database->quote($this->TeleopCargoStored)) .", 
           
            EndGameReturnedToHabitat = " . ((empty($this->EndGameReturnedToHabitat)) ? "0" : $database->quote($this->EndGameReturnedToHabitat)) .", 
            EndGameReturnedToHabitatAttempts = " . ((empty($this->EndGameReturnedToHabitatAttempts)) ? "0" : $database->quote($this->EndGameReturnedToHabitatAttempts)) .", 
           
            BlueAllianceFinalScore = " . ((empty($this->BlueAllianceFinalScore)) ? "0" : $database->quote($this->BlueAllianceFinalScore)) .", 
            RedAllianceFinalScore = " . ((empty($this->RedAllianceFinalScore)) ? "0" : $database->quote($this->RedAllianceFinalScore)) .", 
            DefenseRating = " . ((empty($this->DefenseRating)) ? "0" : $database->quote($this->DefenseRating)) .", 
            OffenseRating = " . ((empty($this->OffenseRating)) ? "0" : $database->quote($this->OffenseRating)) .", 
            DriveRating = " . ((empty($this->DriveRating)) ? "0" : $database->quote($this->DriveRating)) .", 
            Notes = " . ((empty($this->Notes)) ? "NULL" : $database->quote($this->Notes)) .", 
            
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

    /**
     * @returns Matches
     */
    public function getMatch()
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