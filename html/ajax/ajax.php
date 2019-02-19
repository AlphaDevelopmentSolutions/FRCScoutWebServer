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
                if($scoutCard['EndGameReturnedToHabitat'] == "No")
                    $endGameReturnedToHabitat += 0;

                else
                    $endGameReturnedToHabitat += substr($scoutCard['EndGameReturnedToHabitat'], strpos($scoutCard['EndGameReturnedToHabitat'], " "));

                $autoExitHabitatMax = (($scoutCard['AutonomousExitHabitat'] > $autoExitHabitatMax) ? $scoutCard['AutonomousExitHabitat'] : $autoExitHabitatMax);
                $autoHatchPanelsMax = (($scoutCard['AutonomousHatchPanelsSecured'] > $autoHatchPanelsMax) ? $scoutCard['AutonomousHatchPanelsSecured'] : $autoHatchPanelsMax);
                $autoCargoStoredMax = (($scoutCard['AutonomousCargoStored'] > $autoCargoStoredMax) ? $scoutCard['AutonomousCargoStored'] : $autoCargoStoredMax);
                $teleopHatchPanelsMax = (($scoutCard['TeleopHatchPanelsSecured'] > $teleopHatchPanelsMax) ? $scoutCard['TeleopHatchPanelsSecured'] : $teleopHatchPanelsMax);
                $teleopCargoStoredMax = (($scoutCard['TeleopCargoStored'] > $teleopCargoStoredMax) ? $scoutCard['TeleopCargoStored'] : $teleopCargoStoredMax);
                $teleopRocketsCompleteMax = (($scoutCard['TeleopRocketsComplete'] > $teleopRocketsCompleteMax) ? $scoutCard['TeleopRocketsComplete'] : $teleopRocketsCompleteMax);



                if($scoutCard['EndGameReturnedToHabitat'] == "No")
                    $endGameReturnedToHabitatMax = (0 > $endGameReturnedToHabitatMax) ? 0 : $endGameReturnedToHabitatMax;

                else
                    $endGameReturnedToHabitatMax = ((substr($scoutCard['EndGameReturnedToHabitat'], strpos($scoutCard['EndGameReturnedToHabitat'], " ")) > $endGameReturnedToHabitatMax) ? substr($scoutCard['EndGameReturnedToHabitat'], strpos($scoutCard['EndGameReturnedToHabitat'], " ")) : $endGameReturnedToHabitatMax);



                $i++;

            }

            $scoutCardCount = ($i == 0) ? 1 : $i;

            $data[] = $teamNumber;
            $data[] = 'AVG';
            $data[] = round($autoExitHabitat / $scoutCardCount, 2);
            $data[] = round($autoHatchPanels / $scoutCardCount, 2);
            $data[] = round($autoCargoStored / $scoutCardCount, 2);
            $data[] = round($teleopHatchPanels / $scoutCardCount, 2);
            $data[] = round($teleopCargoStored / $scoutCardCount, 2);
            $data[] = round($teleopRocketsComplete / $scoutCardCount, 2);
            $data[] = (($endGameReturnedToHabitat / $scoutCardCount > 0) ? "Level " . round($endGameReturnedToHabitat / $scoutCardCount, 2) : "No");



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
            $data[] = (($endGameReturnedToHabitatMax > 0) ? "Level " . $endGameReturnedToHabitatMax : "No");

            $return_array[] = $data;

        }

        echo json_encode(array(
            "data" => $return_array
        ));

        break;
}