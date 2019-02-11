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
                                      ' . (($this->MatchId == null) ? 'NULL' : $database->quote($this->MatchId)) .',
                                      ' . (($this->TeamId == null) ? 'NULL' : $database->quote($this->TeamId)) .',
                                      ' . (($this->CompletedBy == null) ? 'NULL' : $database->quote($this->CompletedBy)) .',
                                      ' . (($this->BlueAllianceFinalScore == null) ? 'NULL' : $database->quote($this->BlueAllianceFinalScore)) .',
                                      ' . (($this->RedAllianceFinalScore == null) ? 'NULL' : $database->quote($this->RedAllianceFinalScore)) .',
                                      ' . (($this->AutonomousExitHabitat == null) ? 'NULL' : $database->quote($this->AutonomousExitHabitat)) .',
                                      ' . (($this->AutonomousHatchPanelsSecured == null) ? 'NULL' : $database->quote($this->AutonomousHatchPanelsSecured)) .',
                                      ' . (($this->AutonomousCargoStored == null) ? 'NULL' : $database->quote($this->AutonomousCargoStored)) .',
                                      ' . (($this->TeleopHatchPanelsSecured == null) ? 'NULL' : $database->quote($this->TeleopHatchPanelsSecured)) .',
                                      ' . (($this->TeleopCargoStored == null) ? 'NULL' : $database->quote($this->TeleopCargoStored)) .',
                                      ' . (($this->TeleopRocketsCompleted == null) ? 'NULL' : $database->quote($this->TeleopRocketsCompleted)) .',
                                      ' . (($this->EndGameReturnedToHabitat == null) ? 'NULL' : $database->quote($this->EndGameReturnedToHabitat)) .',
                                      ' . (($this->Notes == null) ? 'NULL' : $database->quote($this->Notes)) .',
                                      ' . (($this->CompletedDate == null) ? 'NULL' : $database->quote($this->CompletedDate)) .'
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
            MatchId = " . (($this->MatchId == null) ? "NULL" : $database->quote($this->MatchId)) .", 
            TeamId = " . (($this->TeamId == null) ? "NULL" : $database->quote($this->TeamId)) .", 
            CompletedBy = " . (($this->CompletedBy == null) ? "NULL" : $database->quote($this->CompletedBy)) .", 
            BlueAllianceFinalScore = " . (($this->BlueAllianceFinalScore == null) ? "NULL" : $database->quote($this->BlueAllianceFinalScore)) .", 
            RedAllianceFinalScore = " . (($this->RedAllianceFinalScore == null) ? "NULL" : $database->quote($this->RedAllianceFinalScore)) .", 
            AutonomousExitHabitat = " . (($this->AutonomousExitHabitat == null) ? "NULL" : $database->quote($this->AutonomousExitHabitat)) .", 
            AutonomousHatchPanelsSecured = " . (($this->AutonomousHatchPanelsSecured == null) ? "NULL" : $database->quote($this->AutonomousHatchPanelsSecured)) .", 
            AutonomousCargoStored = " . (($this->AutonomousCargoStored == null) ? "NULL" : $database->quote($this->AutonomousCargoStored)) .", 
            TeleopHatchPanelsSecured = " . (($this->TeleopHatchPanelsSecured == null) ? "NULL" : $database->quote($this->TeleopHatchPanelsSecured)) .", 
            TeleopCargoStored = " . (($this->TeleopCargoStored == null) ? "NULL" : $database->quote($this->TeleopCargoStored)) .", 
            TeleopRocketsCompleted = " . (($this->TeleopRocketsCompleted == null) ? "NULL" : $database->quote($this->TeleopRocketsCompleted)) .", 
            EndGameReturnedToHabitat = " . (($this->EndGameReturnedToHabitat == null) ? "NULL" : $database->quote($this->EndGameReturnedToHabitat)) .", 
            Notes = " . (($this->Notes == null) ? "NULL" : $database->quote($this->Notes)) .", 
            CompletedDate = " . (($this->CompletedDate == null) ? "NULL" : $database->quote($this->CompletedDate)) ."
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