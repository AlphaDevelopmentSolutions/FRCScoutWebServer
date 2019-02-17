<?php
require_once("../config.php");

switch($_POST['action'])
{
    case 'load_stats':

        require_once('../classes/Teams.php');
        require_once('../classes/ScoutCards.php');
        $return_array = array();

        $eventId = $_POST['eventId'];

        foreach (Teams::getTeamsAtEvent($eventId) as $team)
        {
            $data = array();
            $teamNumber = $team['Id'];

            $autoExitHabitat = 0;
            $autoHatchPanels = 0;
            $autoCargoStored = 0;
            $teleopHatchPanels = 0;
            $teleopCargoStored = 0;
            $teleopRocketsComplete = 0;
            $endGameReturnedToHabitat = 0;

            $autoExitHabitatMax = 0;
            $autoHatchPanelsMax = 0;
            $autoCargoStoredMax = 0;
            $teleopHatchPanelsMax = 0;
            $teleopCargoStoredMax = 0;
            $teleopRocketsCompleteMax = 0;
            $endGameReturnedToHabitatMax = 0;

            $i = 0;

            //calc AVG
            foreach (ScoutCards::getScoutCardsForTeam($team['Id'], $eventId) as $scoutCard)
            {
                $autoExitHabitat += $scoutCard['AutonomousExitHabitat'];
                $autoHatchPanels += $scoutCard['AutonomousHatchPanelsSecured'];
                $autoCargoStored += $scoutCard['AutonomousCargoStored'];
                $teleopHatchPanels += $scoutCard['TeleopHatchPanelsSecured'];
                $teleopCargoStored += $scoutCard['TeleopCargoStored'];
                $teleopRocketsComplete += $scoutCard['TeleopRocketsComplete'];
                $endGameReturnedToHabitat += $scoutCard['EndGameReturnedToHabitat'];

                $autoExitHabitatMax = (($scoutCard['AutonomousExitHabitat'] > $autoExitHabitatMax) ? $scoutCard['AutonomousExitHabitat'] : $autoExitHabitatMax);
                $autoHatchPanelsMax = (($scoutCard['AutonomousHatchPanelsSecured'] > $autoExitHabitatMax) ? $scoutCard['AutonomousHatchPanelsSecured'] : $autoExitHabitatMax);;
                $autoCargoStoredMax = (($scoutCard['AutonomousCargoStored'] > $autoExitHabitatMax) ? $scoutCard['AutonomousCargoStored'] : $autoExitHabitatMax);;
                $teleopHatchPanelsMax = (($scoutCard['TeleopHatchPanelsSecured'] > $autoExitHabitatMax) ? $scoutCard['TeleopHatchPanelsSecured'] : $autoExitHabitatMax);;
                $teleopCargoStoredMax = (($scoutCard['TeleopCargoStored'] > $autoExitHabitatMax) ? $scoutCard['TeleopCargoStored'] : $autoExitHabitatMax);;
                $teleopRocketsCompleteMax = (($scoutCard['TeleopRocketsComplete'] > $autoExitHabitatMax) ? $scoutCard['TeleopRocketsComplete'] : $autoExitHabitatMax);;
                $endGameReturnedToHabitatMax = (($scoutCard['EndGameReturnedToHabitat'] > $autoExitHabitatMax) ? $scoutCard['EndGameReturnedToHabitat'] : $autoExitHabitatMax);;

                $i++;

            }

            $scoutCardCount = ($i == 0) ? 1 : $i;

            $data[] = $teamNumber;
            $data[] = 'AVG';
            $data[] = $autoExitHabitat / $scoutCardCount;
            $data[] = $autoHatchPanels / $scoutCardCount;
            $data[] = $autoCargoStored / $scoutCardCount;
            $data[] = $teleopHatchPanels / $scoutCardCount;
            $data[] = $teleopCargoStored / $scoutCardCount;
            $data[] = $teleopRocketsComplete / $scoutCardCount;
            $data[] = $endGameReturnedToHabitat / $scoutCardCount;



            $return_array[] = $data;

            $data = array();
            $data[] = $teamNumber;
            $data[] = 'MAX';
            $data[] = $autoExitHabitatMax;
            $data[] = $autoHatchPanelsMax;
            $data[] = $autoCargoStoredMax;
            $data[] = $teleopHatchPanelsMax;
            $data[] = $teleopCargoStoredMax;
            $data[] = $teleopRocketsCompleteMax;
            $data[] = $endGameReturnedToHabitatMax;

            $return_array[] = $data;

        }

        echo json_encode(array(
            "data" => $return_array
        ));

        break;
}