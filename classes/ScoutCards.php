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

    function save()
    {
        if($this->Id == null)
        {

        }
    }


}

?>