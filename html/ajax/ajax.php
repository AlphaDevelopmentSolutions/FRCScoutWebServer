<?php
require_once("../config.php");

switch($_POST['action'])
{
    case 'load_stats':

        require_once('../classes/Teams.php');
        require_once('../classes/ScoutCards.php');
        require_once('../classes/MatchItemActions.php');
        require_once('../classes/MatchState.php');
        require_once('../classes/Action.php');
        require_once('../classes/ItemType.php');
        $return_array = array();

        $eventId = $_POST['eventId'];

        $removeMin = $_POST['removeMin'];
        $removeAvg = $_POST['removeAvg'];
        $removeMax = $_POST['removeMax'];

        foreach (Teams::getTeamsAtEvent($eventId) as $team)
        {
            $data = array();
            $teamNumber = $team['Id'] . ' - ' . $team['Name'];

            $autoExitHabitatMin = 1000;
            $autoHatchPanelsMin = 1000;
            $autoHatchPanelsAttemptsMin = 1000;
            $autoCargoStoredMin = 1000;
            $autoCargoStoredAttemptsMin = 1000;
            $teleopHatchPanelsMin = 1000;
            $teleopHatchPanelsAttemptsMin = 1000;
            $teleopCargoStoredMin = 1000;
            $teleopCargoStoredAttemptsMin = 1000;
            $endGameReturnedToHabitatMin = 1000;
            $endGameReturnedToHabitatAttemptsMin = 1000;

            $autoExitHabitatMinMatchIds = array();
            $autoHatchPanelsMinMatchIds = array();
            $autoHatchPanelsAttemptsMinMatchIds = array();
            $autoCargoStoredMinMatchIds = array();
            $autoCargoStoredAttemptsMinMatchIds = array();
            $teleopHatchPanelsMinMatchIds = array();
            $teleopHatchPanelsAttemptsMinMatchIds = array();
            $teleopCargoStoredMinMatchIds = array();
            $teleopCargoStoredAttemptsMinMatchIds = array();
            $teleopRocketsCompleteMinMatchIds = array();
            $endGameReturnedToHabitatMinMatchIds = array();
            $endGameReturnedToHabitatAttemptsMinMatchIds = array();

            $autoExitHabitat = 0;
            $autoHatchPanels = 0;
            $autoHatchPanelsAttempts = 0;
            $autoCargoStored = 0;
            $autoCargoStoredAttempts = 0;
            $teleopHatchPanels = 0;
            $teleopHatchPanelsAttempts = 0;
            $teleopCargoStored = 0;
            $teleopCargoStoredAttempts = 0;
            $teleopRocketsComplete = 0;
            $endGameReturnedToHabitat = 0;
            $endGameReturnedToHabitatAttempts = 0;

            $autoExitHabitatMax = 0;
            $autoHatchPanelsMax = 0;
            $autoHatchPanelsAttemptsMax = 0;
            $autoCargoStoredMax = 0;
            $autoCargoStoredAttemptsMax = 0;
            $teleopHatchPanelsMax = 0;
            $teleopHatchPanelsAttemptsMax = 0;
            $teleopCargoStoredMax = 0;
            $teleopCargoStoredAttemptsMax = 0;
            $teleopRocketsCompleteMax = 0;
            $endGameReturnedToHabitatMax = 0;
            $endGameReturnedToHabitatAttemptsMax = 0;

            $autoExitHabitatMaxMatchIds = array();
            $autoHatchPanelsMaxMatchIds = array();
            $autoHatchPanelsAttemptsMaxMatchIds = array();
            $autoCargoStoredMaxMatchIds = array();
            $autoCargoStoredAttemptsMaxMatchIds = array();
            $teleopHatchPanelsMaxMatchIds = array();
            $teleopHatchPanelsAttemptsMaxMatchIds = array();
            $teleopCargoStoredMaxMatchIds = array();
            $teleopCargoStoredAttemptsMaxMatchIds = array();
            $teleopRocketsCompleteMaxMatchIds = array();
            $endGameReturnedToHabitatMaxMatchIds = array();
            $endGameReturnedToHabitatAttemptsMaxMatchIds = array();

            $i = 0;

            foreach (ScoutCards::getScoutCardsForTeam($team['Id'], $eventId) as $scoutCard)
            {

                $totalAutoHatchSecured = 0;
                $totalAutoHatchDropped = 0;
                $totalAutoCargoSecured = 0;
                $totalAutoCargoDropped = 0;

                $totalTeleopHatchSecured = 0;
                $totalTeleopHatchDropped = 0;
                $totalTeleopCargoSecured = 0;
                $totalTeleopCargoDropped = 0;

                //calc totals from match item actions
                foreach(MatchItemActions::getMatchItemActionsForScoutCard($scoutCard['Id']) as $matchItemAction)
                {
                    //calc auto
                    if($matchItemAction['MatchState'] == MatchState::AUTO)
                    {
                        //calc hatches
                        if($matchItemAction['ItemType'] == ItemType::HATCH)
                        {
                            $totalAutoHatchSecured += (($matchItemAction['Action'] == Action::SECURED) ? 1 : 0);
                            $totalAutoHatchDropped += (($matchItemAction['Action'] == Action::DROPPED) ? 1 : 0);
                        }
                        //calc cargo
                        else if($matchItemAction['ItemType'] == ItemType::CARGO)
                        {
                            $totalAutoCargoSecured += (($matchItemAction['Action'] == Action::SECURED) ? 1 : 0);
                            $totalAutoCargoDropped += (($matchItemAction['Action'] == Action::DROPPED) ? 1 : 0);
                        }
                    }
                    //calc teleop
                    else if($matchItemAction['MatchState'] == MatchState::TELEOP)
                    {
                        //calc hatches
                        if($matchItemAction['ItemType'] == ItemType::HATCH)
                        {
                            $totalTeleopHatchSecured += (($matchItemAction['Action'] == Action::SECURED) ? 1 : 0);
                            $totalTeleopHatchDropped += (($matchItemAction['Action'] == Action::DROPPED) ? 1 : 0);
                        }
                        //calc cargo
                        else if($matchItemAction['ItemType'] == ItemType::CARGO)
                        {
                            $totalTeleopCargoSecured += (($matchItemAction['Action'] == Action::SECURED) ? 1 : 0);
                            $totalTeleopCargoDropped += (($matchItemAction['Action'] == Action::DROPPED) ? 1 : 0);
                        }
                    }
                }

                //calc min
                if($scoutCard['AutonomousExitHabitat'] <= $autoExitHabitatMin)
                {
                    $autoExitHabitatMinMatchIds = (($scoutCard['AutonomousExitHabitat'] < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                    $autoExitHabitatMin = $scoutCard['AutonomousExitHabitat'];
                    $autoExitHabitatMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalAutoHatchSecured <= $autoHatchPanelsMin)
                {
                    $autoHatchPanelsMinMatchIds = (($totalAutoHatchSecured < $autoHatchPanelsMin) ? array() : $autoHatchPanelsMinMatchIds);
                    $autoHatchPanelsMin = $totalAutoHatchSecured;
                    $autoHatchPanelsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalAutoCargoSecured <= $autoCargoStoredMin)
                {
                    $autoCargoStoredMinMatchIds = (($totalAutoCargoSecured < $autoCargoStoredMin) ? array() : $autoCargoStoredMinMatchIds);
                    $autoCargoStoredMin = $totalAutoCargoSecured;
                    $autoCargoStoredMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalTeleopHatchSecured <= $teleopHatchPanelsMin)
                {
                    $teleopHatchPanelsMinMatchIds = (($totalTeleopHatchSecured < $teleopHatchPanelsMin) ? array() : $teleopHatchPanelsMinMatchIds);
                    $teleopHatchPanelsMin = $totalTeleopHatchSecured;
                    $teleopHatchPanelsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalTeleopCargoSecured <= $teleopCargoStoredMin)
                {
                    $teleopCargoStoredMinMatchIds = (($totalTeleopCargoSecured < $teleopCargoStoredMin) ? array() : $teleopCargoStoredMinMatchIds);
                    $teleopCargoStoredMin = $totalTeleopCargoSecured;
                    $teleopCargoStoredMinMatchIds[] = $scoutCard['MatchId'];
                }
                
                if($totalAutoHatchDropped <= $autoHatchPanelsAttemptsMin)
                {
                    $autoHatchPanelsAttemptsMinMatchIds = (($totalAutoHatchDropped < $autoHatchPanelsAttemptsMin) ? array() : $autoHatchPanelsAttemptsMinMatchIds);
                    $autoHatchPanelsAttemptsMin = $totalAutoHatchDropped;
                    $autoHatchPanelsAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalAutoCargoDropped <= $autoCargoStoredAttemptsMin)
                {
                    $autoCargoStoredAttemptsMinMatchIds = (($totalAutoCargoDropped < $autoCargoStoredAttemptsMin) ? array() : $autoCargoStoredAttemptsMinMatchIds);
                    $autoCargoStoredAttemptsMin = $totalAutoCargoDropped;
                    $autoCargoStoredAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalTeleopHatchDropped <= $teleopHatchPanelsAttemptsMin)
                {
                    $teleopHatchPanelsAttemptsMinMatchIds = (($totalTeleopHatchDropped < $teleopHatchPanelsAttemptsMin) ? array() : $teleopHatchPanelsAttemptsMinMatchIds);
                    $teleopHatchPanelsAttemptsMin = $totalTeleopHatchDropped;
                    $teleopHatchPanelsAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalTeleopCargoDropped <= $teleopCargoStoredAttemptsMin)
                {
                    $teleopCargoStoredAttemptsMinMatchIds = (($totalTeleopCargoDropped < $teleopCargoStoredAttemptsMin) ? array() : $teleopCargoStoredAttemptsMinMatchIds);
                    $teleopCargoStoredAttemptsMin = $totalTeleopCargoDropped;
                    $teleopCargoStoredAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if(empty($scoutCard['EndGameReturnedToHabitat']))
                {
                    if (0 <= $endGameReturnedToHabitatMin)
                    {
                        $endGameReturnedToHabitatMinMatchIds = ((0 < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                        $endGameReturnedToHabitatMin = 0;
                        $endGameReturnedToHabitatMinMatchIds[] = $scoutCard['MatchId'];
                    }
                }
                else if($scoutCard['EndGameReturnedToHabitat'] <= $endGameReturnedToHabitatMin)
                {
                    $endGameReturnedToHabitatMinMatchIds = (($scoutCard['EndGameReturnedToHabitat'] < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                    $endGameReturnedToHabitatMin = $scoutCard['EndGameReturnedToHabitat'];
                    $endGameReturnedToHabitatMinMatchIds[] = $scoutCard['MatchId'];
                }

                if(empty($scoutCard['EndGameReturnedToHabitatAttempts']))
                {
                    if (0 <= $endGameReturnedToHabitatAttemptsMin)
                    {
                        $endGameReturnedToHabitatAttemptsMinMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] < $endGameReturnedToHabitatAttemptsMin) ? array() : $endGameReturnedToHabitatAttemptsMinMatchIds);
                        $endGameReturnedToHabitatAttemptsMin = 0;
                        $endGameReturnedToHabitatAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                    }
                }
                else if($scoutCard['EndGameReturnedToHabitatAttempts'] <= $endGameReturnedToHabitatAttemptsMin)
                {
                    $endGameReturnedToHabitatAttemptsMinMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] < $endGameReturnedToHabitatAttemptsMin) ? array() : $endGameReturnedToHabitatAttemptsMinMatchIds);
                    $endGameReturnedToHabitatAttemptsMin = $scoutCard['EndGameReturnedToHabitatAttempts'];
                    $endGameReturnedToHabitatAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                }

                //calc avg
                $autoExitHabitat += $scoutCard['AutonomousExitHabitat'];
                $autoHatchPanels += $totalAutoHatchSecured;
                $autoHatchPanelsAttempts += $totalAutoHatchDropped;
                $autoCargoStored += $totalAutoCargoSecured;
                $autoCargoStoredAttempts += $totalAutoCargoDropped;
                $teleopHatchPanels += $totalTeleopHatchSecured;
                $teleopHatchPanelsAttempts += $totalTeleopHatchDropped;
                $teleopCargoStored += $totalTeleopCargoSecured;
                $teleopCargoStoredAttempts += $totalTeleopCargoDropped;

                if(!empty($scoutCard['EndGameReturnedToHabitat']))
                    $endGameReturnedToHabitat += $scoutCard['EndGameReturnedToHabitat'];

                if(!empty($scoutCard['EndGameReturnedToHabitatAttempts']))
                    $endGameReturnedToHabitatAttempts += $scoutCard['EndGameReturnedToHabitatAttempts'];

                //calc max
                if($scoutCard['AutonomousExitHabitat'] >= $autoExitHabitatMax)
                {
                    $autoExitHabitatMaxMatchIds = (($scoutCard['AutonomousExitHabitat'] > $autoExitHabitatMax) ? array() : $autoExitHabitatMaxMatchIds);
                    $autoExitHabitatMax = $scoutCard['AutonomousExitHabitat'];
                    $autoExitHabitatMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalAutoHatchSecured >= $autoHatchPanelsMax)
                {
                    $autoHatchPanelsMaxMatchIds = (($totalAutoHatchSecured > $autoHatchPanelsMax) ? array() : $autoHatchPanelsMaxMatchIds);
                    $autoHatchPanelsMax = $totalAutoHatchSecured;
                    $autoHatchPanelsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalAutoCargoSecured >= $autoCargoStoredMax)
                {
                    $autoCargoStoredMaxMatchIds = (($totalAutoCargoSecured > $autoCargoStoredMax) ? array() : $autoCargoStoredMaxMatchIds);
                    $autoCargoStoredMax = $totalAutoCargoSecured;
                    $autoCargoStoredMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalTeleopHatchSecured >= $teleopHatchPanelsMax)
                {
                    $teleopHatchPanelsMaxMatchIds = (($totalTeleopHatchSecured > $teleopHatchPanelsMax) ? array() : $teleopHatchPanelsMaxMatchIds);
                    $teleopHatchPanelsMax = $totalTeleopHatchSecured;
                    $teleopHatchPanelsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalTeleopCargoSecured >= $teleopCargoStoredMax)
                {
                    $teleopCargoStoredMaxMatchIds = (($totalTeleopCargoSecured > $teleopCargoStoredMax) ? array() : $teleopCargoStoredMaxMatchIds);
                    $teleopCargoStoredMax = $totalTeleopCargoSecured;
                    $teleopCargoStoredMaxMatchIds[] = $scoutCard['MatchId'];
                }
                
                if($totalAutoHatchDropped >= $autoHatchPanelsAttemptsMax)
                {
                    $autoHatchPanelsAttemptsMaxMatchIds = (($totalAutoHatchDropped > $autoHatchPanelsAttemptsMax) ? array() : $autoHatchPanelsAttemptsMaxMatchIds);
                    $autoHatchPanelsAttemptsMax = $totalAutoHatchDropped;
                    $autoHatchPanelsAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalAutoCargoDropped >= $autoCargoStoredAttemptsMax)
                {
                    $autoCargoStoredAttemptsMaxMatchIds = (($totalAutoCargoDropped > $autoCargoStoredAttemptsMax) ? array() : $autoCargoStoredAttemptsMaxMatchIds);
                    $autoCargoStoredAttemptsMax = $totalAutoCargoDropped;
                    $autoCargoStoredAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalTeleopHatchDropped >= $teleopHatchPanelsAttemptsMax)
                {
                    $teleopHatchPanelsAttemptsMaxMatchIds = (($totalTeleopHatchDropped > $teleopHatchPanelsAttemptsMax) ? array() : $teleopHatchPanelsAttemptsMaxMatchIds);
                    $teleopHatchPanelsAttemptsMax = $totalTeleopHatchDropped;
                    $teleopHatchPanelsAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($totalTeleopCargoDropped >= $teleopCargoStoredAttemptsMax)
                {
                    $teleopCargoStoredAttemptsMaxMatchIds = (($totalTeleopCargoDropped > $teleopCargoStoredAttemptsMax) ? array() : $teleopCargoStoredAttemptsMaxMatchIds);
                    $teleopCargoStoredAttemptsMax = $totalTeleopCargoDropped;
                    $teleopCargoStoredAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                }


                if(empty($scoutCard['EndGameReturnedToHabitat'])) {
                    if (0 >= $endGameReturnedToHabitatMax) {
                        $endGameReturnedToHabitatMaxMatchIds = (($scoutCard['EndGameReturnedToHabitat'] > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                        $endGameReturnedToHabitatMax = 0;
                        $endGameReturnedToHabitatMaxMatchIds[] = $scoutCard['MatchId'];
                    }
                }
                else if($scoutCard['EndGameReturnedToHabitat'] >= $endGameReturnedToHabitatMax)
                {
                    $endGameReturnedToHabitatMaxMatchIds = (($scoutCard['EndGameReturnedToHabitat'] > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                    $endGameReturnedToHabitatMax = $scoutCard['EndGameReturnedToHabitat'];
                    $endGameReturnedToHabitatMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if(empty($scoutCard['EndGameReturnedToHabitatAttempts']))
                {
                    if (0 >= $endGameReturnedToHabitatAttemptsMax) {
                        $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                        $endGameReturnedToHabitatAttemptsMax = 0;
                        $endGameReturnedToHabitatAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                    }
                }
                else if($scoutCard['EndGameReturnedToHabitatAttempts'] >= $endGameReturnedToHabitatAttemptsMax)
                {
                    $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                    $endGameReturnedToHabitatAttemptsMax = $scoutCard['EndGameReturnedToHabitatAttempts'];
                }
                    $i++;

            }

            //min data
            if($removeMin != 'true') {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $teamNumber .'">' . $teamNumber . '</a>';
                $data[] = 'MIN';
                $data[] = $autoExitHabitatMin == 0 ? 'No' : 'Level ' . $autoExitHabitatMin;
                $data[] = $autoHatchPanelsMin;
                $data[] = $autoHatchPanelsAttemptsMin;
                $data[] = $autoCargoStoredMin;
                $data[] = $autoCargoStoredAttemptsMin;
                $data[] = $teleopHatchPanelsMin;
                $data[] = $teleopHatchPanelsAttemptsMin;
                $data[] = $teleopCargoStoredMin;
                $data[] = $teleopCargoStoredAttemptsMin;
                $data[] = (($endGameReturnedToHabitatMin > 0) ? "Level " . $endGameReturnedToHabitatMin : "No");
                $data[] = (($endGameReturnedToHabitatAttemptsMin > 0) ? "Level " . $endGameReturnedToHabitatAttemptsMin : "No");

                $data['autoExitHabitatMinMatchIds'] = $autoExitHabitatMinMatchIds;
                $data['autoHatchPanelsMinMatchIds'] = $autoHatchPanelsMinMatchIds;
                $data['autoHatchPanelsAttemptsMinMatchIds'] = $autoHatchPanelsAttemptsMinMatchIds;
                $data['autoCargoStoredMinMatchIds'] = $autoCargoStoredMinMatchIds;
                $data['autoCargoStoredAttemptsMinMatchIds'] = $autoCargoStoredAttemptsMinMatchIds;
                $data['teleopHatchPanelsMinMatchIds'] = $teleopHatchPanelsMinMatchIds;
                $data['teleopHatchPanelsAttemptsMinMatchIds'] = $teleopHatchPanelsAttemptsMinMatchIds;
                $data['teleopCargoStoredMinMatchIds'] = $teleopCargoStoredMinMatchIds;
                $data['teleopCargoStoredAttemptsMinMatchIds'] = $teleopCargoStoredAttemptsMinMatchIds;
                $data['teleopRocketsCompleteMinMatchIds'] = $teleopRocketsCompleteMinMatchIds;
                $data['endGameReturnedToHabitatMinMatchIds'] = $endGameReturnedToHabitatMinMatchIds;
                $data['endGameReturnedToHabitatAttemptsMinMatchIds'] = $endGameReturnedToHabitatAttemptsMinMatchIds;

                $return_array[] = $data;
            }

            //avg data
            if($removeAvg != 'true') {
                $scoutCardCount = ($i == 0) ? 1 : $i;

                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $teamNumber .'">' . $teamNumber . '</a>';;
                $data[] = 'AVG';
                $data[] = (round($autoExitHabitat / $scoutCardCount, 2) ? "Level " . round($autoExitHabitat / $scoutCardCount, 2) : "No");
                $data[] = round($autoHatchPanels / $scoutCardCount, 2);
                $data[] = round($autoHatchPanelsAttempts / $scoutCardCount, 2);
                $data[] = round($autoCargoStored / $scoutCardCount, 2);
                $data[] = round($autoCargoStoredAttempts / $scoutCardCount, 2);
                $data[] = round($teleopHatchPanels / $scoutCardCount, 2);
                $data[] = round($teleopHatchPanelsAttempts / $scoutCardCount, 2);
                $data[] = round($teleopCargoStored / $scoutCardCount, 2);
                $data[] = round($teleopCargoStoredAttempts / $scoutCardCount, 2);
                $data[] = (($endGameReturnedToHabitat / $scoutCardCount > 0) ? "Level " . round($endGameReturnedToHabitat / $scoutCardCount, 2) : "No");
                $data[] = (($endGameReturnedToHabitatAttempts / $scoutCardCount > 0) ? "Level " . round($endGameReturnedToHabitatAttempts / $scoutCardCount, 2) : "No");

                $return_array[] = $data;
            }

            //max data
            if($removeMax != 'true') {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $teamNumber .'">' . $teamNumber . '</a>';;
                $data[] = 'MAX';
                $data[] = $autoExitHabitatMax == 0 ? 'No' : 'Level ' . $autoExitHabitatMax;;
                $data[] = $autoHatchPanelsMax;
                $data[] = $autoHatchPanelsAttemptsMax;
                $data[] = $autoCargoStoredMax;
                $data[] = $autoCargoStoredAttemptsMax;
                $data[] = $teleopHatchPanelsMax;
                $data[] = $teleopHatchPanelsAttemptsMax;
                $data[] = $teleopCargoStoredMax;
                $data[] = $teleopCargoStoredAttemptsMax;
                $data[] = (($endGameReturnedToHabitatMax > 0) ? "Level " . $endGameReturnedToHabitatMax : "No");
                $data[] = (($endGameReturnedToHabitatAttemptsMax > 0) ? "Level " . $endGameReturnedToHabitatAttemptsMax : "No");

                $data['autoExitHabitatMaxMatchIds'] = $autoExitHabitatMaxMatchIds;
                $data['autoHatchPanelsMaxMatchIds'] = $autoHatchPanelsMaxMatchIds;
                $data['autoHatchPanelsAttemptsMaxMatchIds'] = $autoHatchPanelsAttemptsMaxMatchIds;
                $data['autoCargoStoredMaxMatchIds'] = $autoCargoStoredMaxMatchIds;
                $data['autoCargoStoredAttemptsMaxMatchIds'] = $autoCargoStoredAttemptsMaxMatchIds;
                $data['teleopHatchPanelsMaxMatchIds'] = $teleopHatchPanelsMaxMatchIds;
                $data['teleopHatchPanelsAttemptsMaxMatchIds'] = $teleopHatchPanelsAttemptsMaxMatchIds;
                $data['teleopCargoStoredMaxMatchIds'] = $teleopCargoStoredMaxMatchIds;
                $data['teleopCargoStoredAttemptsMaxMatchIds'] = $teleopCargoStoredAttemptsMaxMatchIds;
                $data['teleopRocketsCompleteMaxMatchIds'] = $teleopRocketsCompleteMaxMatchIds;
                $data['endGameReturnedToHabitatMaxMatchIds'] = $endGameReturnedToHabitatMaxMatchIds;
                $data['endGameReturnedToHabitatAttemptsMaxMatchIds'] = $endGameReturnedToHabitatAttemptsMaxMatchIds;

                $return_array[] = $data;
            }

        }

        echo json_encode(array(
            "data" => $return_array
        ));

        break;
}