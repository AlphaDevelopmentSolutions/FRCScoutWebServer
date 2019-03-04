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
    public $AutonomousExitHabitat;
    public $AutonomousHatchPanelsSecured;
    public $AutonomousHatchPanelsSecuredAttempts;
    public $AutonomousCargoStored;
    public $AutonomousCargoStoredAttempts;
    public $TeleopHatchPanelsSecured;
    public $TeleopHatchPanelsSecuredAttempts;
    public $TeleopCargoStored;
    public $TeleopCargoStoredAttempts;
    public $TeleopRocketsCompleted;
    public $EndGameReturnedToHabitat;
    public $EndGameReturnedToHabitatAttempts;
    public $Notes;
    public $CompletedDate;

    private static $TABLE_NAME = 'scout_cards';

    function load($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM '.$this::$TABLE_NAME.' WHERE '.'id = '.$database->quote($id);
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
            $sql = 'INSERT INTO ' . ScoutCards::$TABLE_NAME . ' 
                                      (
                                      MatchId,
                                      TeamId,
                                      EventId,
                                      AllianceColor,
                                      CompletedBy,
                                      BlueAllianceFinalScore,
                                      RedAllianceFinalScore,
                                      AutonomousExitHabitat,
                                      AutonomousHatchPanelsSecured,
                                      AutonomousHatchPanelsSecuredAttempts,
                                      AutonomousCargoStored,
                                      AutonomousCargoStoredAttempts,
                                      TeleopHatchPanelsSecured,
                                      TeleopHatchPanelsSecuredAttempts,
                                      TeleopCargoStored,
                                      TeleopCargoStoredAttempts,
                                      TeleopRocketsCompleted,
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
                                      ' . ((empty($this->AutonomousExitHabitat)) ? '0' : $database->quote($this->AutonomousExitHabitat)) .',
                                      ' . ((empty($this->AutonomousHatchPanelsSecured)) ? '0' : $database->quote($this->AutonomousHatchPanelsSecured)) .',
                                      ' . ((empty($this->AutonomousHatchPanelsSecuredAttempts)) ? '0' : $database->quote($this->AutonomousHatchPanelsSecuredAttempts)) .',
                                      ' . ((empty($this->AutonomousCargoStored)) ? '0' : $database->quote($this->AutonomousCargoStored)) .',
                                      ' . ((empty($this->AutonomousCargoStoredAttempts)) ? '0' : $database->quote($this->AutonomousCargoStoredAttempts)) .',
                                      ' . ((empty($this->TeleopHatchPanelsSecured)) ? '0' : $database->quote($this->TeleopHatchPanelsSecured)) .',
                                      ' . ((empty($this->TeleopHatchPanelsSecuredAttempts)) ? '0' : $database->quote($this->TeleopHatchPanelsSecuredAttempts)) .',
                                      ' . ((empty($this->TeleopCargoStored)) ? '0' : $database->quote($this->TeleopCargoStored)) .',
                                      ' . ((empty($this->TeleopCargoStoredAttempts)) ? '0' : $database->quote($this->TeleopCargoStoredAttempts)) .',
                                      ' . ((empty($this->TeleopRocketsCompleted)) ? '0' : $database->quote($this->TeleopRocketsCompleted)) .',
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
            $sql = "UPDATE " . ScoutCards::$TABLE_NAME . " SET 
            MatchId = " . ((empty($this->MatchId)) ? "NULL" : $database->quote($this->MatchId)) .", 
            TeamId = " . ((empty($this->TeamId)) ? "NULL" : $database->quote($this->TeamId)) .", 
            EventId = " . ((empty($this->EventId)) ? "NULL" : $database->quote($this->EventId)) .", 
            AllianceColor = " . ((empty($this->AllianceColor)) ? "NULL" : $database->quote($this->AllianceColor)) .", 
            CompletedBy = " . ((empty($this->CompletedBy)) ? "NULL" : $database->quote($this->CompletedBy)) .", 
            BlueAllianceFinalScore = " . ((empty($this->BlueAllianceFinalScore)) ? "NULL" : $database->quote($this->BlueAllianceFinalScore)) .", 
            RedAllianceFinalScore = " . ((empty($this->RedAllianceFinalScore)) ? "NULL" : $database->quote($this->RedAllianceFinalScore)) .", 
            AutonomousExitHabitat = " . ((empty($this->AutonomousExitHabitat)) ? "NULL" : $database->quote($this->AutonomousExitHabitat)) .", 
            AutonomousHatchPanelsSecured = " . ((empty($this->AutonomousHatchPanelsSecured)) ? "NULL" : $database->quote($this->AutonomousHatchPanelsSecured)) .", 
            AutonomousHatchPanelsSecuredAttempts = " . ((empty($this->AutonomousHatchPanelsSecuredAttempts)) ? "NULL" : $database->quote($this->AutonomousHatchPanelsSecuredAttempts)) .", 
            AutonomousCargoStored = " . ((empty($this->AutonomousCargoStored)) ? "NULL" : $database->quote($this->AutonomousCargoStored)) .", 
            AutonomousCargoStoredAttempts = " . ((empty($this->AutonomousCargoStoredAttempts)) ? "NULL" : $database->quote($this->AutonomousCargoStoredAttempts)) .", 
            TeleopHatchPanelsSecured = " . ((empty($this->TeleopHatchPanelsSecured)) ? "NULL" : $database->quote($this->TeleopHatchPanelsSecured)) .", 
            TeleopHatchPanelsSecuredAttempts = " . ((empty($this->TeleopHatchPanelsSecuredAttempts)) ? "NULL" : $database->quote($this->TeleopHatchPanelsSecuredAttempts)) .", 
            TeleopCargoStored = " . ((empty($this->TeleopCargoStored)) ? "NULL" : $database->quote($this->TeleopCargoStored)) .", 
            TeleopCargoStoredAttempts = " . ((empty($this->TeleopCargoStoredAttempts)) ? "NULL" : $database->quote($this->TeleopCargoStoredAttempts)) .", 
            TeleopRocketsCompleted = " . ((empty($this->TeleopRocketsCompleted)) ? "NULL" : $database->quote($this->TeleopRocketsCompleted)) .", 
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
                      scout_cards 
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
                      scout_cards 
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