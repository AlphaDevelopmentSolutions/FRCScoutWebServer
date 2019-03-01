<?php
require_once("../config.php");

switch($_POST['action'])
{
    case 'load_stats':

        require_once('../classes/Teams.php');
        require_once('../classes/ScoutCards.php');
        $return_array = array();

        $eventId = $_POST['eventId'];

        $removeMin = $_POST['removeMin'];
        $removeAvg = $_POST['removeAvg'];
        $removeMax = $_POST['removeMax'];

        foreach (Teams::getTeamsAtEvent($eventId) as $team)
        {
            $data = array();
            $teamNumber = $team['Id'];

            $autoExitHabitatMin = 0;
            $autoHatchPanelsMin = 0;
            $autoCargoStoredMin = 0;
            $teleopHatchPanelsMin = 0;
            $teleopCargoStoredMin = 0;
            $teleopRocketsCompleteMin = 0;
            $endGameReturnedToHabitatMin = 0;

            $autoExitHabitatMinMatchIds = array();
            $autoHatchPanelsMinMatchIds = array();
            $autoCargoStoredMinMatchIds = array();
            $teleopHatchPanelsMinMatchIds = array();
            $teleopCargoStoredMinMatchIds = array();
            $teleopRocketsCompleteMinMatchIds = array();
            $endGameReturnedToHabitatMinMatchIds = array();

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

            $autoExitHabitatMaxMatchIds = array();
            $autoHatchPanelsMaxMatchIds = array();
            $autoCargoStoredMaxMatchIds = array();
            $teleopHatchPanelsMaxMatchIds = array();
            $teleopCargoStoredMaxMatchIds = array();
            $teleopRocketsCompleteMaxMatchIds = array();
            $endGameReturnedToHabitatMaxMatchIds = array();

            $i = 0;

            foreach (ScoutCards::getScoutCardsForTeam($team['Id'], $eventId) as $scoutCard)
            {

                //calc min
                if($scoutCard['AutonomousExitHabitat'] <= $autoExitHabitatMin)
                {
                    $autoExitHabitatMinMatchIds = (($scoutCard['AutonomousExitHabitat'] < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                    $autoExitHabitatMin = $scoutCard['AutonomousExitHabitat'];
                    $autoExitHabitatMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['AutonomousHatchPanelsSecured'] <= $autoHatchPanelsMin)
                {
                    $autoHatchPanelsMinMatchIds = (($scoutCard['AutonomousHatchPanelsSecured'] < $autoHatchPanelsMin) ? array() : $autoHatchPanelsMinMatchIds);
                    $autoHatchPanelsMin = $scoutCard['AutonomousHatchPanelsSecured'];
                    $autoHatchPanelsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['AutonomousCargoStored'] <= $autoCargoStoredMin)
                {
                    $autoCargoStoredMinMatchIds = (($scoutCard['AutonomousCargoStored'] < $autoCargoStoredMin) ? array() : $autoCargoStoredMinMatchIds);
                    $autoCargoStoredMin = $scoutCard['AutonomousCargoStored'];
                    $autoCargoStoredMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopHatchPanelsSecured'] <= $teleopHatchPanelsMin)
                {
                    $teleopHatchPanelsMinMatchIds = (($scoutCard['TeleopHatchPanelsSecured'] < $teleopHatchPanelsMin) ? array() : $teleopHatchPanelsMinMatchIds);
                    $teleopHatchPanelsMin = $scoutCard['TeleopHatchPanelsSecured'];
                    $teleopHatchPanelsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopCargoStored'] <= $teleopCargoStoredMin)
                {
                    $teleopCargoStoredMinMatchIds = (($scoutCard['TeleopCargoStored'] < $teleopCargoStoredMin) ? array() : $teleopCargoStoredMinMatchIds);
                    $teleopCargoStoredMin = $scoutCard['TeleopCargoStored'];
                    $teleopCargoStoredMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopRocketsComplete'] <= $teleopRocketsCompleteMin)
                {
                    $teleopRocketsCompleteMinMatchIds = (($scoutCard['TeleopRocketsComplete'] < $teleopRocketsCompleteMin) ? array() : $teleopRocketsCompleteMinMatchIds);
                    $teleopRocketsCompleteMin = $scoutCard['TeleopRocketsComplete'];
                    $teleopRocketsCompleteMinMatchIds[] = $scoutCard['MatchId'];
                }
                
                
                if($scoutCard['EndGameReturnedToHabitat'] == "No") {
                    if (0 <= $endGameReturnedToHabitatMin) {
                        $endGameReturnedToHabitatMinMatchIds = (($scoutCard['EndGameReturnedToHabitat'] < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                        $endGameReturnedToHabitatMin = 0;
                        $endGameReturnedToHabitatMinMatchIds[] = $scoutCard['MatchId'];
                    }
                }
                else if(substr($scoutCard['EndGameReturnedToHabitat'], strpos($scoutCard['EndGameReturnedToHabitat'], " ")) <= $endGameReturnedToHabitatMin)
                {
                    $endGameReturnedToHabitatMinMatchIds = (($scoutCard['EndGameReturnedToHabitat'] < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                    $endGameReturnedToHabitatMin = substr($scoutCard['EndGameReturnedToHabitat'], strpos($scoutCard['EndGameReturnedToHabitat'], " "));
                    $endGameReturnedToHabitatMinMatchIds[] = $scoutCard['MatchId'];
                }

                //calc avg
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

                //calc max
                if($scoutCard['AutonomousExitHabitat'] >= $autoExitHabitatMax)
                {
                    $autoExitHabitatMaxMatchIds = (($scoutCard['AutonomousExitHabitat'] > $autoExitHabitatMax) ? array() : $autoExitHabitatMaxMatchIds);
                    $autoExitHabitatMax = $scoutCard['AutonomousExitHabitat'];
                    $autoExitHabitatMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['AutonomousHatchPanelsSecured'] >= $autoHatchPanelsMax)
                {
                    $autoHatchPanelsMaxMatchIds = (($scoutCard['AutonomousHatchPanelsSecured'] > $autoHatchPanelsMax) ? array() : $autoHatchPanelsMaxMatchIds);
                    $autoHatchPanelsMax = $scoutCard['AutonomousHatchPanelsSecured'];
                    $autoHatchPanelsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['AutonomousCargoStored'] >= $autoCargoStoredMax)
                {
                    $autoCargoStoredMaxMatchIds = (($scoutCard['AutonomousCargoStored'] > $autoCargoStoredMax) ? array() : $autoCargoStoredMaxMatchIds);
                    $autoCargoStoredMax = $scoutCard['AutonomousCargoStored'];
                    $autoCargoStoredMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopHatchPanelsSecured'] >= $teleopHatchPanelsMax)
                {
                    $teleopHatchPanelsMaxMatchIds = (($scoutCard['TeleopHatchPanelsSecured'] > $teleopHatchPanelsMax) ? array() : $teleopHatchPanelsMaxMatchIds);
                    $teleopHatchPanelsMax = $scoutCard['TeleopHatchPanelsSecured'];
                    $teleopHatchPanelsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopCargoStored'] >= $teleopCargoStoredMax)
                {
                    $teleopCargoStoredMaxMatchIds = (($scoutCard['TeleopCargoStored'] > $teleopCargoStoredMax) ? array() : $teleopCargoStoredMaxMatchIds);
                    $teleopCargoStoredMax = $scoutCard['TeleopCargoStored'];
                    $teleopCargoStoredMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopRocketsComplete'] >= $teleopRocketsCompleteMax)
                {
                    $teleopRocketsCompleteMaxMatchIds = (($scoutCard['TeleopRocketsComplete'] > $teleopRocketsCompleteMax) ? array() : $teleopRocketsCompleteMaxMatchIds);
                    $teleopRocketsCompleteMax = $scoutCard['TeleopRocketsComplete'];
                    $teleopRocketsCompleteMaxMatchIds[] = $scoutCard['MatchId'];
                }


                if($scoutCard['EndGameReturnedToHabitat'] == "No") {
                    if (0 >= $endGameReturnedToHabitatMax) {
                        $endGameReturnedToHabitatMaxMatchIds = (($scoutCard['EndGameReturnedToHabitat'] > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                        $endGameReturnedToHabitatMax = 0;
                        $endGameReturnedToHabitatMaxMatchIds[] = $scoutCard['MatchId'];
                    }
                }
                else if(substr($scoutCard['EndGameReturnedToHabitat'], strpos($scoutCard['EndGameReturnedToHabitat'], " ")) >= $endGameReturnedToHabitatMax)
                {
                    $endGameReturnedToHabitatMaxMatchIds = (($scoutCard['EndGameReturnedToHabitat'] > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                    $endGameReturnedToHabitatMax = substr($scoutCard['EndGameReturnedToHabitat'], strpos($scoutCard['EndGameReturnedToHabitat'], " "));
                    $endGameReturnedToHabitatMaxMatchIds[] = $scoutCard['MatchId'];
                }
                
                $i++;

            }

            if($removeMin == 'false') {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $teamNumber .'">' . $teamNumber . '</a>';
                $data[] = 'MIN';
                $data[] = $autoExitHabitatMin;
                $data[] = $autoHatchPanelsMin;
                $data[] = $autoCargoStoredMin;
                $data[] = $teleopHatchPanelsMin;
                $data[] = $teleopCargoStoredMin;
                $data[] = $teleopRocketsCompleteMin;
                $data[] = (($endGameReturnedToHabitatMin > 0) ? "Level " . $endGameReturnedToHabitatMin : "No");
                
                $data['autoExitHabitatMinMatchIds'] = $autoExitHabitatMinMatchIds;
                $data['autoHatchPanelsMinMatchIds'] = $autoHatchPanelsMinMatchIds;
                $data['autoCargoStoredMinMatchIds'] = $autoCargoStoredMinMatchIds;
                $data['teleopHatchPanelsMinMatchIds'] = $teleopHatchPanelsMinMatchIds;
                $data['teleopCargoStoredMinMatchIds'] = $teleopCargoStoredMinMatchIds;
                $data['teleopRocketsCompleteMinMatchIds'] = $teleopRocketsCompleteMinMatchIds;
                $data['endGameReturnedToHabitatMinMatchIds'] = $endGameReturnedToHabitatMinMatchIds;

                $return_array[] = $data;
            }


            if($removeAvg == 'false') {
                $scoutCardCount = ($i == 0) ? 1 : $i;

                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $teamNumber .'">' . $teamNumber . '</a>';;
                $data[] = 'AVG';
                $data[] = round($autoExitHabitat / $scoutCardCount, 2);
                $data[] = round($autoHatchPanels / $scoutCardCount, 2);
                $data[] = round($autoCargoStored / $scoutCardCount, 2);
                $data[] = round($teleopHatchPanels / $scoutCardCount, 2);
                $data[] = round($teleopCargoStored / $scoutCardCount, 2);
                $data[] = round($teleopRocketsComplete / $scoutCardCount, 2);
                $data[] = (($endGameReturnedToHabitat / $scoutCardCount > 0) ? "Level " . round($endGameReturnedToHabitat / $scoutCardCount, 2) : "No");

                $return_array[] = $data;
            }

            if($removeMax == 'false') {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $teamNumber .'">' . $teamNumber . '</a>';;
                $data[] = 'MAX';
                $data[] = $autoExitHabitatMax;
                $data[] = $autoHatchPanelsMax;
                $data[] = $autoCargoStoredMax;
                $data[] = $teleopHatchPanelsMax;
                $data[] = $teleopCargoStoredMax;
                $data[] = $teleopRocketsCompleteMax;
                $data[] = (($endGameReturnedToHabitatMax > 0) ? "Level " . $endGameReturnedToHabitatMax : "No");

                $data['autoExitHabitatMaxMatchIds'] = $autoExitHabitatMaxMatchIds;
                $data['autoHatchPanelsMaxMatchIds'] = $autoHatchPanelsMaxMatchIds;
                $data['autoCargoStoredMaxMatchIds'] = $autoCargoStoredMaxMatchIds;
                $data['teleopHatchPanelsMaxMatchIds'] = $teleopHatchPanelsMaxMatchIds;
                $data['teleopCargoStoredMaxMatchIds'] = $teleopCargoStoredMaxMatchIds;
                $data['teleopRocketsCompleteMaxMatchIds'] = $teleopRocketsCompleteMaxMatchIds;
                $data['endGameReturnedToHabitatMaxMatchIds'] = $endGameReturnedToHabitatMaxMatchIds;

                $return_array[] = $data;
            }

        }

        echo json_encode(array(
            "data" => $return_array
        ));

        break;
}