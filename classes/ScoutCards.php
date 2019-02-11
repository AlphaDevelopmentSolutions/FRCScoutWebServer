<?php

class ScoutCards
{
    public $Id;
    public $MatchId;
    public $TeamId;
    public $CompletedBy;
    public $BlueAllianceFinalScore;
    public $RedAllianceFinalScore;
    public $AutonomousExitHabitat;
    public $AutonomousHatchPanelsSecured;
    public $AutonomousCargoStored;
    public $TeleopHatchPanelsSecured;
    public $TeleopCargoStored;
    public $TeleopRocketsCompleted;
    public $EndGameReturnedToHabitat;
    public $Notes;
    public $CompletedDate;

    private static $TABLE_NAME = 'scout_cards';

    function save()
    {
        $database = new Database();

        if(empty($this->Id))
        {
            $sql = 'INSERT INTO ' . ScoutCards::$TABLE_NAME . ' 
                                      (
                                      MatchId,
                                      TeamId,
                                      CompletedBy,
                                      BlueAllianceFinalScore,
                                      RedAllianceFinalScore,
                                      AutonomousExitHabitat,
                                      AutonomousHatchPanelsSecured,
                                      AutonomousCargoStored,
                                      TeleopHatchPanelsSecured,
                                      TeleopCargoStored,
                                      TeleopRocketsCompleted,
                                      EndGameReturnedToHabitat,
                                      Notes,
                                      CompletedDate
                                      )
                                      VALUES 
                                      (
                                      ' . ((empty($this->MatchId)) ? 'NULL' : $database->quote($this->MatchId)) .',
                                      ' . ((empty($this->TeamId)) ? 'NULL' : $database->quote($this->TeamId)) .',
                                      ' . ((empty($this->CompletedBy)) ? 'NULL' : $database->quote($this->CompletedBy)) .',
                                      ' . ((empty($this->BlueAllianceFinalScore)) ? 'NULL' : $database->quote($this->BlueAllianceFinalScore)) .',
                                      ' . ((empty($this->RedAllianceFinalScore)) ? 'NULL' : $database->quote($this->RedAllianceFinalScore)) .',
                                      ' . ((empty($this->AutonomousExitHabitat)) ? 'NULL' : $database->quote($this->AutonomousExitHabitat)) .',
                                      ' . ((empty($this->AutonomousHatchPanelsSecured)) ? 'NULL' : $database->quote($this->AutonomousHatchPanelsSecured)) .',
                                      ' . ((empty($this->AutonomousCargoStored)) ? 'NULL' : $database->quote($this->AutonomousCargoStored)) .',
                                      ' . ((empty($this->TeleopHatchPanelsSecured)) ? 'NULL' : $database->quote($this->TeleopHatchPanelsSecured)) .',
                                      ' . ((empty($this->TeleopCargoStored)) ? 'NULL' : $database->quote($this->TeleopCargoStored)) .',
                                      ' . ((empty($this->TeleopRocketsCompleted)) ? 'NULL' : $database->quote($this->TeleopRocketsCompleted)) .',
                                      ' . ((empty($this->EndGameReturnedToHabitat)) ? 'NULL' : $database->quote($this->EndGameReturnedToHabitat)) .',
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
            CompletedBy = " . ((empty($this->CompletedBy)) ? "NULL" : $database->quote($this->CompletedBy)) .", 
            BlueAllianceFinalScore = " . ((empty($this->BlueAllianceFinalScore)) ? "NULL" : $database->quote($this->BlueAllianceFinalScore)) .", 
            RedAllianceFinalScore = " . ((empty($this->RedAllianceFinalScore)) ? "NULL" : $database->quote($this->RedAllianceFinalScore)) .", 
            AutonomousExitHabitat = " . ((empty($this->AutonomousExitHabitat)) ? "NULL" : $database->quote($this->AutonomousExitHabitat)) .", 
            AutonomousHatchPanelsSecured = " . ((empty($this->AutonomousHatchPanelsSecured)) ? "NULL" : $database->quote($this->AutonomousHatchPanelsSecured)) .", 
            AutonomousCargoStored = " . ((empty($this->AutonomousCargoStored)) ? "NULL" : $database->quote($this->AutonomousCargoStored)) .", 
            TeleopHatchPanelsSecured = " . ((empty($this->TeleopHatchPanelsSecured)) ? "NULL" : $database->quote($this->TeleopHatchPanelsSecured)) .", 
            TeleopCargoStored = " . ((empty($this->TeleopCargoStored)) ? "NULL" : $database->quote($this->TeleopCargoStored)) .", 
            TeleopRocketsCompleted = " . ((empty($this->TeleopRocketsCompleted)) ? "NULL" : $database->quote($this->TeleopRocketsCompleted)) .", 
            EndGameReturnedToHabitat = " . ((empty($this->EndGameReturnedToHabitat)) ? "NULL" : $database->quote($this->EndGameReturnedToHabitat)) .", 
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


}

?>