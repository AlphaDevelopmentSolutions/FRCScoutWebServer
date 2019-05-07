<?php
require_once("../config.php");
require_once("../classes/Events.php");
require_once("../classes/Teams.php");
switch($_POST['action'])
{
    case 'load_stats':
        $return_array = array();

        $eventId = $_POST['eventId'];
        $teamIds = json_decode($_POST['teamIds']);

        $event = Events::withId($eventId);
        $teams = null;

        if(!empty($teamIds))
            foreach($teamIds as $teamId)
                $teams[] = Teams::withId($teamId);

        //get all the scout cards from the event specified
        foreach ($event->getScoutCards(null, $team) as $scoutCard)
        {
            $arrayKey = $scoutCard->TeamId;

            //for each scout card object returned, get the key and value from it
            foreach ($scoutCard as $key => $value)
            {
                //filter out the fields we do not want
                if ($key != 'Id' &&
                    $key != 'MatchId' &&
                    $key != 'TeamId' &&
                    $key != 'EventId' &&
                    $key != 'AllianceColor' &&
                    $key != 'CompletedBy' &&
                    $key != 'Notes' &&
                    $key != 'CompletedDate'
                )
                {
                    //at the teamid index, and key, compile whatever key it is at with the value. Essentially creating a running total
                    $return_array[$arrayKey][$key] = $return_array[$arrayKey][$key] + $value;

                    //check if we need to nullify any offense ratings
                    if ($key == 'OffenseRating' && $value == 0)
                        $return_array[$arrayKey]['NulledOffenseRatings'] = ((empty($return_array[$arrayKey]['NulledOffenseRatings'])) ? 1 : $return_array[$arrayKey]['NulledOffenseRatings'] + 1);

                    //check if we need to nullify any defense ratings
                    else if ($key == 'DefenseRating' && $value == 0)
                        $return_array[$arrayKey]['NulledDefenseRatings'] = ((empty($return_array[$arrayKey]['NulledDefenseRatings'])) ? 1 : $return_array[$arrayKey]['NulledDefenseRatings'] + 1);
                }
            }

            //compile a running total for how many cards a team has, to calculate an average
            $return_array[$arrayKey]['CardCount'] = ((empty($return_array[$arrayKey]['CardCount'])) ? 1 : $return_array[$arrayKey]['CardCount'] + 1);

        }

        //once the totals have been calculated iterate through to calculate averages
        foreach($return_array as $teamId => $stats)
        {
            //for each stat inside each team, get the key of that stat and value
            foreach($stats as $key => $value)
            {
                //don't change the values specified
                if($key != 'CardCount' && $key != 'NulledOffenseRatings' && $key != 'NulledDefenseRatings')
                {
                    //if we aren't calculating the offense or defense rating, don't worry about nulled ratings
                    //essentially get the total calculated above and divide it by the number of scout cards
                    if($key != 'OffenseRating' && $key != 'DefenseRating')
                        $return_array[$teamId][$key] = round($return_array[$teamId][$key] / $return_array[$teamId]['CardCount'], 2);

                    //if we are calculating offense rating, check for nulled ratings
                    else if($key == 'OffenseRating'  && $return_array[$teamId][$key] != 0)
                        $return_array[$teamId][$key] = round($return_array[$teamId][$key] / ($return_array[$teamId]['CardCount'] - $return_array[$teamId]['NulledOffenseRatings']), 2);

                    //if we are calculating defense rating, check for nulled ratings
                    else if($key == 'DefenseRating' && $return_array[$teamId][$key] != 0)
                        $return_array[$teamId][$key] = round($return_array[$teamId][$key] / ($return_array[$teamId]['CardCount'] - $return_array[$teamId]['NulledDefenseRatings']), 2);
                }
            }

            //once all the averages for a team have been calculated
            //iterate through the array and create a running total of averages into the EventAvg key
            foreach($stats as $key => $value)
                $return_array['EventAvg'][$key] += $return_array[$teamId][$key];

            $return_array['EventAvg']['TeamCount'] = ((empty($return_array['EventAvg']['TeamCount'])) ? 1 : $return_array['EventAvg']['TeamCount'] + 1);
        }


        //once all the event averages have been totalled, divide each average by the number of teams at the event
        foreach ($return_array['EventAvg'] as $key => $value)
        {
            if($key != 'TeamCount')
                $return_array['EventAvg'][$key] = $return_array['EventAvg'][$key] / $return_array['EventAvg']['TeamCount'];
        }


        //if the teams array is not empty, teams were searched
        if(!empty($teams))
        {
            //iterate through each searched team
            foreach($return_array as $key => $value)
            {
                //skip over the eventavg key
                if($key != 'EventAvg')
                {
                    $searchedTeamInTeams = false;

                    //check if the team that was searched is the same as the current key we are on
                    foreach ($teams as $searchedTeams)
                    {
                        if ($key == $searchedTeams->Id)
                            $searchedTeamInTeams = true;
                    }

                    //if the current key is not a searched team, delete it from the array
                    if (!$searchedTeamInTeams)
                        unset($return_array[$key]);
                }
            }
        }

        //only 1 team specified, give a match breakdown
        if(sizeof($teams) == 1)
        {
            $team = $teams[0];
            unset($return_array[$team->Id]);

            $scoutCards = array();

            foreach($event->getMatches(null, $team) as $match)
            {
                foreach($match->getScoutCards() as $scoutCard)
                    $scoutCards[] = $scoutCard;

            }

            //get all the scout cards from the event specified
            foreach ($scoutCards as $scoutCard)
            {
                $match = Matches::withId($scoutCard->MatchId);
                $arrayKey = $match->MatchNumber;

                //for each scout card object returned, get the key and value from it
                foreach ($scoutCard as $key => $value)
                {
                    //filter out the fields we do not want
                    if ($key != 'Id' &&
                        $key != 'MatchId' &&
                        $key != 'TeamId' &&
                        $key != 'EventId' &&
                        $key != 'AllianceColor' &&
                        $key != 'CompletedBy' &&
                        $key != 'Notes' &&
                        $key != 'CompletedDate'
                    )
                    {
                        //at the teamid index, and key, compile whatever key it is at with the value. Essentially creating a running total
                        $return_array[$scoutCard->TeamId][$arrayKey][$key] = $return_array[$scoutCard->TeamId][$arrayKey][$key] + $value;
                        $return_array['MatchAvgs'][$arrayKey][$key] = $return_array['MatchAvgs'][$arrayKey][$key] + $value;

                        //check if we need to nullify any offense ratings
                        if ($key == 'OffenseRating' && $value == 0)
                        {
                            $return_array[$scoutCard->TeamId][$arrayKey]['NulledOffenseRatings'] = ((empty($return_array[$scoutCard->TeamId][$arrayKey]['NulledOffenseRatings'])) ? 1 : $return_array[$scoutCard->TeamId][$arrayKey]['NulledOffenseRatings'] + 1);
                            $return_array['MatchAvgs'][$arrayKey]['NulledOffenseRatings'] = ((empty($return_array['MatchAvgs'][$arrayKey]['NulledOffenseRatings'])) ? 1 : $return_array['MatchAvgs'][$arrayKey]['NulledOffenseRatings'] + 1);

                        }

                        //check if we need to nullify any defense ratings
                        else if ($key == 'DefenseRating' && $value == 0)
                        {
                            $return_array[$scoutCard->TeamId][$arrayKey]['NulledDefenseRatings'] = ((empty($return_array[$scoutCard->TeamId][$arrayKey]['NulledDefenseRatings'])) ? 1 : $return_array[$scoutCard->TeamId][$arrayKey]['NulledDefenseRatings'] + 1);
                            $return_array['MatchAvgs'][$arrayKey]['NulledDefenseRatings'] = ((empty($return_array['MatchAvgs'][$arrayKey]['NulledDefenseRatings'])) ? 1 : $return_array['MatchAvgs'][$arrayKey]['NulledDefenseRatings'] + 1);
                        }
                    }
                }

                //compile a running total for how many cards a team has, to calculate an average
                $return_array[$scoutCard->TeamId][$arrayKey]['CardCount'] = ((empty($return_array[$scoutCard->TeamId][$arrayKey]['CardCount'])) ? 1 : $return_array[$scoutCard->TeamId][$arrayKey]['CardCount'] + 1);
                $return_array['MatchAvgs'][$arrayKey]['CardCount'] = ((empty($return_array['MatchAvgs'][$arrayKey]['CardCount'])) ? 1 : $return_array['MatchAvgs'][$arrayKey]['CardCount'] + 1);

            }

            //once the totals have been calculated iterate through to calculate averages
            foreach($return_array as $teamId => $value)
            {
                if($teamId != 'EventAvg')
                {
                    foreach($value as $matchKey => $matchValue)
                    {
                        //for each stat inside each team, get the key of that stat and value
                        foreach ($matchValue as $key => $value)
                        {
                            //don't change the values specified
                            if ($key != 'CardCount' && $key != 'NulledOffenseRatings' && $key != 'NulledDefenseRatings')
                            {
                                //if we aren't calculating the offense or defense rating, don't worry about nulled ratings
                                //essentially get the total calculated above and divide it by the number of scout cards
                                if ($key != 'OffenseRating' && $key != 'DefenseRating')
                                    $return_array[$teamId][$matchKey][$key] = round($return_array[$teamId][$matchKey][$key] / $return_array[$teamId][$matchKey]['CardCount'], 2);

                                //if we are calculating offense rating, check for nulled ratings
                                else if ($key == 'OffenseRating' && $return_array[$teamId][$matchKey][$key] != 0)
                                    $return_array[$teamId][$matchKey][$key] = round($return_array[$teamId][$matchKey][$key] / ($return_array[$teamId][$matchKey]['CardCount'] - $return_array[$teamId][$matchKey]['NulledOffenseRatings']), 2);

                                //if we are calculating defense rating, check for nulled ratings
                                else if ($key == 'DefenseRating' && $return_array[$teamId][$matchKey][$key] != 0)
                                    $return_array[$teamId][$matchKey][$key] = round($return_array[$teamId][$matchKey][$key] / ($return_array[$teamId][$matchKey]['CardCount'] - $return_array[$teamId][$matchKey]['NulledDefenseRatings']), 2);
                            }
                        }
                    }

                }
            }


            //iterate through each searched team
            foreach($return_array as $key => $value)
            {
                //skip over the eventavg key
                if($key != 'EventAvg' && $key != 'MatchAvgs')
                {
                    $searchedTeamInTeams = false;

                    //check if the team that was searched is the same as the current key we are on
                    foreach ($teams as $searchedTeams)
                    {
                        if ($key == $searchedTeams->Id)
                            $searchedTeamInTeams = true;
                    }

                    //if the current key is not a searched team, delete it from the array
                    if (!$searchedTeamInTeams)
                        unset($return_array[$key]);
                }
            }

            //iterate through each searched team
            foreach($return_array['MatchAvgs'] as $key => $value)
            {
                $correctMatch = false;

                //check if the team that was searched is the same as the current key we are on
                foreach ($return_array[$team->Id] as $correctMatchKey => $value)
                {
                    if ($key == $correctMatchKey)
                        $correctMatch = true;
                }

                //if the current key is not a searched team, delete it from the array
                if (!$correctMatch)
                    unset($return_array['MatchAvgs'][$key]);

            }

        }


        //sort the array by team ids
        ksort($return_array);

        echo json_encode($return_array);

        break;

    case 'load_stats_legacy':

        require_once('../classes/Teams.php');
        require_once('../classes/ScoutCards.php');
        require_once('../classes/Matches.php');
        $return_array = array();

        $eventId = $_POST['eventId'];

        $event = Events::withId($eventId);

        $removeMin = $_POST['removeMin'];
        $removeAvg = $_POST['removeAvg'];
        $removeMax = $_POST['removeMax'];

        foreach ($event->getTeams() as $team)
        {

            $data = array();

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
            $postGameDefenseRatingMin = 5;
            $postGameOffenseRatingMin = 5;
            $postGameDriveRatingMin = 5;
            
            $autoExitHabitatMinMatchIds = array();
            $autoHatchPanelsMinMatchIds = array();
            $autoHatchPanelsAttemptsMinMatchIds = array();
            $autoCargoStoredMinMatchIds = array();
            $autoCargoStoredAttemptsMinMatchIds = array();
            $teleopHatchPanelsMinMatchIds = array();
            $teleopHatchPanelsAttemptsMinMatchIds = array();
            $teleopCargoStoredMinMatchIds = array();
            $teleopCargoStoredAttemptsMinMatchIds = array();
            $endGameReturnedToHabitatMinMatchIds = array();
            $endGameReturnedToHabitatAttemptsMinMatchIds = array();
            $postGameDefenseRatingMinMatchIds = array();
            $postGameOffenseRatingMinMatchIds = array();
            $postGameDriveRatingMinMatchIds = array();

            $autoExitHabitat = 0;
            $autoHatchPanels = 0;
            $autoHatchPanelsAttempts = 0;
            $autoCargoStored = 0;
            $autoCargoStoredAttempts = 0;
            $teleopHatchPanels = 0;
            $teleopHatchPanelsAttempts = 0;
            $teleopCargoStored = 0;
            $teleopCargoStoredAttempts = 0;
            $endGameReturnedToHabitat = 0;
            $endGameReturnedToHabitatAttempts = 0;
            $postGameDefenseRating = 0;
            $postGameOffenseRating = 0;
            $postGameDriveRating = 0;

            $autoExitHabitatMax = 0;
            $autoHatchPanelsMax = 0;
            $autoHatchPanelsAttemptsMax = 0;
            $autoCargoStoredMax = 0;
            $autoCargoStoredAttemptsMax = 0;
            $teleopHatchPanelsMax = 0;
            $teleopHatchPanelsAttemptsMax = 0;
            $teleopCargoStoredMax = 0;
            $teleopCargoStoredAttemptsMax = 0;
            $endGameReturnedToHabitatMax = 0;
            $endGameReturnedToHabitatAttemptsMax = 0;
            $postGameDefenseRatingMax = 0;
            $postGameOffenseRatingMax = 0;
            $postGameDriveRatingMax = 0;

            $autoExitHabitatMaxMatchIds = array();
            $autoHatchPanelsMaxMatchIds = array();
            $autoHatchPanelsAttemptsMaxMatchIds = array();
            $autoCargoStoredMaxMatchIds = array();
            $autoCargoStoredAttemptsMaxMatchIds = array();
            $teleopHatchPanelsMaxMatchIds = array();
            $teleopHatchPanelsAttemptsMaxMatchIds = array();
            $teleopCargoStoredMaxMatchIds = array();
            $teleopCargoStoredAttemptsMaxMatchIds = array();
            $endGameReturnedToHabitatMaxMatchIds = array();
            $endGameReturnedToHabitatAttemptsMaxMatchIds = array();
            $postGameDefenseRatingMaxMatchIds = array();
            $postGameOffenseRatingMaxMatchIds = array();
            $postGameDriveRatingMaxMatchIds = array();

            $i = 0;
            $nulledDefenseRatings = 0;
            $nulledOffenseRatings = 0;

            foreach ($event->getScoutCards(null, $team) as $scoutCard)
            {

                $match = Matches::withId($scoutCard->MatchId);

                //calc min
                if($scoutCard->AutonomousExitHabitat == 1)
                {
                    if($scoutCard->PreGameStartingLevel  <= $autoExitHabitatMin)
                    {
                        $autoExitHabitatMinMatchIds = (($scoutCard->PreGameStartingLevel < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                        $autoExitHabitatMin = $scoutCard->PreGameStartingLevel;
                        $autoExitHabitatMinMatchIds[] = $match->toString();
                    }
                }
                else if(0 <= $autoExitHabitatMin)
                {
                    $autoExitHabitatMinMatchIds = ((0 < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                    $autoExitHabitatMin = 0;
                    $autoExitHabitatMinMatchIds[] = $match->toString();
                }

                if($scoutCard->AutonomousHatchPanelsSecured <= $autoHatchPanelsMin)
                {
                    $autoHatchPanelsMinMatchIds = (($scoutCard->AutonomousHatchPanelsSecured < $autoHatchPanelsMin) ? array() : $autoHatchPanelsMinMatchIds);
                    $autoHatchPanelsMin = $scoutCard->AutonomousHatchPanelsSecured;
                    $autoHatchPanelsMinMatchIds[] = $match->toString();
                }

                if($scoutCard->AutonomousCargoStored <= $autoCargoStoredMin)
                {
                    $autoCargoStoredMinMatchIds = (($scoutCard->AutonomousCargoStored < $autoCargoStoredMin) ? array() : $autoCargoStoredMinMatchIds);
                    $autoCargoStoredMin = $scoutCard->AutonomousCargoStored;
                    $autoCargoStoredMinMatchIds[] = $match->toString();
                }

                if($scoutCard->TeleopHatchPanelsSecured <= $teleopHatchPanelsMin)
                {
                    $teleopHatchPanelsMinMatchIds = (($scoutCard->TeleopHatchPanelsSecured < $teleopHatchPanelsMin) ? array() : $teleopHatchPanelsMinMatchIds);
                    $teleopHatchPanelsMin = $scoutCard->TeleopHatchPanelsSecured;
                    $teleopHatchPanelsMinMatchIds[] = $match->toString();
                }

                if($scoutCard->TeleopCargoStored <= $teleopCargoStoredMin)
                {
                    $teleopCargoStoredMinMatchIds = (($scoutCard->TeleopCargoStored < $teleopCargoStoredMin) ? array() : $teleopCargoStoredMinMatchIds);
                    $teleopCargoStoredMin = $scoutCard->TeleopCargoStored;
                    $teleopCargoStoredMinMatchIds[] = $match->toString();
                }

                if($scoutCard->AutonomousHatchPanelsSecuredAttempts <= $autoHatchPanelsAttemptsMin)
                {
                    $autoHatchPanelsAttemptsMinMatchIds = (($scoutCard->AutonomousHatchPanelsSecuredAttempts < $autoHatchPanelsAttemptsMin) ? array() : $autoHatchPanelsAttemptsMinMatchIds);
                    $autoHatchPanelsAttemptsMin = $scoutCard->AutonomousHatchPanelsSecuredAttempts;
                    $autoHatchPanelsAttemptsMinMatchIds[] = $match->toString();
                }

                if($scoutCard->AutonomousCargoStoredAttempts <= $autoCargoStoredAttemptsMin)
                {
                    $autoCargoStoredAttemptsMinMatchIds = (($scoutCard->AutonomousCargoStoredAttempts < $autoCargoStoredAttemptsMin) ? array() : $autoCargoStoredAttemptsMinMatchIds);
                    $autoCargoStoredAttemptsMin = $scoutCard->AutonomousCargoStoredAttempts;
                    $autoCargoStoredAttemptsMinMatchIds[] = $match->toString();
                }

                if($scoutCard->TeleopHatchPanelsSecuredAttempts <= $teleopHatchPanelsAttemptsMin)
                {
                    $teleopHatchPanelsAttemptsMinMatchIds = (($scoutCard->TeleopHatchPanelsSecuredAttempts < $teleopHatchPanelsAttemptsMin) ? array() : $teleopHatchPanelsAttemptsMinMatchIds);
                    $teleopHatchPanelsAttemptsMin = $scoutCard->TeleopHatchPanelsSecuredAttempts;
                    $teleopHatchPanelsAttemptsMinMatchIds[] = $match->toString();
                }

                if($scoutCard->TeleopCargoStoredAttempts <= $teleopCargoStoredAttemptsMin)
                {
                    $teleopCargoStoredAttemptsMinMatchIds = (($scoutCard->TeleopCargoStoredAttempts < $teleopCargoStoredAttemptsMin) ? array() : $teleopCargoStoredAttemptsMinMatchIds);
                    $teleopCargoStoredAttemptsMin = $scoutCard->TeleopCargoStoredAttempts;
                    $teleopCargoStoredAttemptsMinMatchIds[] = $match->toString();
                }

                if(empty($scoutCard->EndGameReturnedToHabitat))
                {
                    if (0 <= $endGameReturnedToHabitatMin) {
                        $endGameReturnedToHabitatMinMatchIds = (($scoutCard->EndGameReturnedToHabitat < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                        $endGameReturnedToHabitatMin = 0;
                        $endGameReturnedToHabitatMinMatchIds[] = $match->toString();
                    }
                }
                else if($scoutCard->EndGameReturnedToHabitat <= $endGameReturnedToHabitatMin)
                {
                    $endGameReturnedToHabitatMinMatchIds = (($scoutCard->EndGameReturnedToHabitat < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                    $endGameReturnedToHabitatMin = $scoutCard->EndGameReturnedToHabitat;
                    $endGameReturnedToHabitatMinMatchIds[] = $match->toString();
                }

                if(empty($scoutCard->EndGameReturnedToHabitatAttempts)) {
                    if (0 <= $endGameReturnedToHabitatAttemptsMin) {
                        $endGameReturnedToHabitatAttemptsMinMatchIds = (($scoutCard->EndGameReturnedToHabitatAttempts < $endGameReturnedToHabitatAttemptsMin) ? array() : $endGameReturnedToHabitatAttemptsMinMatchIds);
                        $endGameReturnedToHabitatAttemptsMin = 0;
                        $endGameReturnedToHabitatAttemptsMinMatchIds[] = $match->toString();
                    }
                }
                else if($scoutCard->EndGameReturnedToHabitatAttempts <= $endGameReturnedToHabitatAttemptsMin)
                {
                    $endGameReturnedToHabitatAttemptsMinMatchIds = (($scoutCard->EndGameReturnedToHabitatAttempts < $endGameReturnedToHabitatAttemptsMin) ? array() : $endGameReturnedToHabitatAttemptsMinMatchIds);
                    $endGameReturnedToHabitatAttemptsMin = $scoutCard->EndGameReturnedToHabitatAttempts;
                    $endGameReturnedToHabitatAttemptsMinMatchIds[] = $match->toString();
                }

                if($scoutCard->DefenseRating <= $postGameDefenseRatingMin && $scoutCard->DefenseRating != 0)
                {
                    $postGameDefenseRatingMinMatchIds = (($scoutCard->DefenseRating < $postGameDefenseRatingMin) ? array() : $postGameDefenseRatingMinMatchIds);
                    $postGameDefenseRatingMin = $scoutCard->DefenseRating;
                    $postGameDefenseRatingMinMatchIds[] = $match->toString();
                }

                if($scoutCard->OffenseRating <= $postGameOffenseRatingMin && $scoutCard->OffenseRating != 0)
                {
                    $postGameOffenseRatingMinMatchIds = (($scoutCard->OffenseRating < $postGameOffenseRatingMin) ? array() : $postGameOffenseRatingMinMatchIds);
                    $postGameOffenseRatingMin = $scoutCard->OffenseRating;
                    $postGameOffenseRatingMinMatchIds[] = $match->toString();
                }

                if($scoutCard->DriveRating <= $postGameDriveRatingMin)
                {
                    $postGameDriveRatingMinMatchIds = (($scoutCard->DriveRating < $postGameDriveRatingMin) ? array() : $postGameDriveRatingMinMatchIds);
                    $postGameDriveRatingMin = $scoutCard->DriveRating;
                    $postGameDriveRatingMinMatchIds[] = $match->toString();
                }

                //calc avg
                if($scoutCard->AutonomousExitHabitat == 1)
                    $autoExitHabitat += $scoutCard->PreGameStartingLevel;

                $autoHatchPanels += $scoutCard->AutonomousHatchPanelsSecured;
                $autoHatchPanelsAttempts += $scoutCard->AutonomousHatchPanelsSecuredAttempts;
                $autoCargoStored += $scoutCard->AutonomousCargoStored;
                $autoCargoStoredAttempts += $scoutCard->AutonomousCargoStoredAttempts;

                $teleopHatchPanels += $scoutCard->TeleopHatchPanelsSecured;
                $teleopHatchPanelsAttempts += $scoutCard->TeleopHatchPanelsSecuredAttempts;
                $teleopCargoStored += $scoutCard->TeleopCargoStored;
                $teleopCargoStoredAttempts += $scoutCard->TeleopCargoStoredAttempts;

                $postGameDefenseRating += $scoutCard->DefenseRating;
                $postGameOffenseRating += $scoutCard->OffenseRating;
                $postGameDriveRating += $scoutCard->DriveRating;

                $nulledDefenseRatings = $scoutCard->DefenseRating == 0 ? $nulledDefenseRatings + 1 : $nulledDefenseRatings;
                $nulledOffenseRatings = $scoutCard->OffenseRating == 0 ? $nulledOffenseRatings + 1 : $nulledOffenseRatings;

                if(!empty($scoutCard->EndGameReturnedToHabitat))
                    $endGameReturnedToHabitat += $scoutCard->EndGameReturnedToHabitat;

                if(!empty($scoutCard->EndGameReturnedToHabitatAttempts))
                    $endGameReturnedToHabitatAttempts += $scoutCard->EndGameReturnedToHabitatAttempts;


                //calc max
                if($scoutCard->AutonomousExitHabitat == 1)
                {
                    if($scoutCard->PreGameStartingLevel  >= $autoExitHabitatMax)
                    {
                        $autoExitHabitatMaxMatchIds = (($scoutCard->PreGameStartingLevel > $autoExitHabitatMax) ? array() : $autoExitHabitatMaxMatchIds);
                        $autoExitHabitatMax = $scoutCard->PreGameStartingLevel;
                        $autoExitHabitatMaxMatchIds[] = $match->toString();
                    }
                }
                else if(0 >= $autoExitHabitatMax)
                {
                    $autoExitHabitatMaxMatchIds = ((0 > $autoExitHabitatMax) ? array() : $autoExitHabitatMaxMatchIds);
                    $autoExitHabitatMax = 0;
                    $autoExitHabitatMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->AutonomousHatchPanelsSecured >= $autoHatchPanelsMax)
                {
                    $autoHatchPanelsMaxMatchIds = (($scoutCard->AutonomousHatchPanelsSecured > $autoHatchPanelsMax) ? array() : $autoHatchPanelsMaxMatchIds);
                    $autoHatchPanelsMax = $scoutCard->AutonomousHatchPanelsSecured;
                    $autoHatchPanelsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->AutonomousCargoStored >= $autoCargoStoredMax)
                {
                    $autoCargoStoredMaxMatchIds = (($scoutCard->AutonomousCargoStored > $autoCargoStoredMax) ? array() : $autoCargoStoredMaxMatchIds);
                    $autoCargoStoredMax = $scoutCard->AutonomousCargoStored;
                    $autoCargoStoredMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->TeleopHatchPanelsSecured >= $teleopHatchPanelsMax)
                {
                    $teleopHatchPanelsMaxMatchIds = (($scoutCard->TeleopHatchPanelsSecured > $teleopHatchPanelsMax) ? array() : $teleopHatchPanelsMaxMatchIds);
                    $teleopHatchPanelsMax = $scoutCard->TeleopHatchPanelsSecured;
                    $teleopHatchPanelsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->TeleopCargoStored >= $teleopCargoStoredMax)
                {
                    $teleopCargoStoredMaxMatchIds = (($scoutCard->TeleopCargoStored > $teleopCargoStoredMax) ? array() : $teleopCargoStoredMaxMatchIds);
                    $teleopCargoStoredMax = $scoutCard->TeleopCargoStored;
                    $teleopCargoStoredMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->AutonomousHatchPanelsSecuredAttempts >= $autoHatchPanelsAttemptsMax)
                {
                    $autoHatchPanelsAttemptsMaxMatchIds = (($scoutCard->AutonomousHatchPanelsSecuredAttempts > $autoHatchPanelsAttemptsMax) ? array() : $autoHatchPanelsAttemptsMaxMatchIds);
                    $autoHatchPanelsAttemptsMax = $scoutCard->AutonomousHatchPanelsSecuredAttempts;
                    $autoHatchPanelsAttemptsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->AutonomousCargoStoredAttempts >= $autoCargoStoredAttemptsMax)
                {
                    $autoCargoStoredAttemptsMaxMatchIds = (($scoutCard->AutonomousCargoStoredAttempts > $autoCargoStoredAttemptsMax) ? array() : $autoCargoStoredAttemptsMaxMatchIds);
                    $autoCargoStoredAttemptsMax = $scoutCard->AutonomousCargoStoredAttempts;
                    $autoCargoStoredAttemptsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->TeleopHatchPanelsSecuredAttempts >= $teleopHatchPanelsAttemptsMax)
                {
                    $teleopHatchPanelsAttemptsMaxMatchIds = (($scoutCard->TeleopHatchPanelsSecuredAttempts > $teleopHatchPanelsAttemptsMax) ? array() : $teleopHatchPanelsAttemptsMaxMatchIds);
                    $teleopHatchPanelsAttemptsMax = $scoutCard->TeleopHatchPanelsSecuredAttempts;
                    $teleopHatchPanelsAttemptsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->TeleopCargoStoredAttempts >= $teleopCargoStoredAttemptsMax)
                {
                    $teleopCargoStoredAttemptsMaxMatchIds = (($scoutCard->TeleopCargoStoredAttempts > $teleopCargoStoredAttemptsMax) ? array() : $teleopCargoStoredAttemptsMaxMatchIds);
                    $teleopCargoStoredAttemptsMax = $scoutCard->TeleopCargoStoredAttempts;
                    $teleopCargoStoredAttemptsMaxMatchIds[] = $match->toString();
                }

                if(empty($scoutCard->EndGameReturnedToHabitat)) {
                    if (0 >= $endGameReturnedToHabitatMax) {
                        $endGameReturnedToHabitatMaxMatchIds = (($scoutCard->EndGameReturnedToHabitat > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                        $endGameReturnedToHabitatMax = 0;
                        $endGameReturnedToHabitatMaxMatchIds[] = $match->toString();
                    }
                }
                else if($scoutCard->EndGameReturnedToHabitat >= $endGameReturnedToHabitatMax)
                {
                    $endGameReturnedToHabitatMaxMatchIds = (($scoutCard->EndGameReturnedToHabitat > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                    $endGameReturnedToHabitatMax = $scoutCard->EndGameReturnedToHabitat;
                    $endGameReturnedToHabitatMaxMatchIds[] = $match->toString();
                }

                if(empty($scoutCard->EndGameReturnedToHabitatAttempts)) {
                    if (0 >= $endGameReturnedToHabitatAttemptsMax) {
                        $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard->EndGameReturnedToHabitatAttempts > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                        $endGameReturnedToHabitatAttemptsMax = 0;
                        $endGameReturnedToHabitatAttemptsMaxMatchIds[] = $match->toString();
                    }
                }
                else if($scoutCard->EndGameReturnedToHabitatAttempts >= $endGameReturnedToHabitatAttemptsMax) {
                    $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard->EndGameReturnedToHabitatAttempts > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                    $endGameReturnedToHabitatAttemptsMax = $scoutCard->EndGameReturnedToHabitatAttempts;
                    $endGameReturnedToHabitatAttemptsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->DefenseRating >= $postGameDefenseRatingMax && $scoutCard->DefenseRating != 0)
                {
                    $postGameDefenseRatingMaxMatchIds = (($scoutCard->DefenseRating < $postGameDefenseRatingMax) ? array() : $postGameDefenseRatingMaxMatchIds);
                    $postGameDefenseRatingMax = $scoutCard->DefenseRating;
                    $postGameDefenseRatingMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->OffenseRating >= $postGameOffenseRatingMax && $scoutCard->OffenseRating != 0)
                {
                    $postGameOffenseRatingMaxMatchIds = (($scoutCard->OffenseRating < $postGameOffenseRatingMax) ? array() : $postGameOffenseRatingMaxMatchIds);
                    $postGameOffenseRatingMax = $scoutCard->OffenseRating;
                    $postGameOffenseRatingMaxMatchIds[] = $match->toString();
                }

                if($scoutCard->DriveRating >= $postGameDriveRatingMax)
                {
                    $postGameDriveRatingMaxMatchIds = (($scoutCard->DriveRating < $postGameDriveRatingMax) ? array() : $postGameDriveRatingMaxMatchIds);
                    $postGameDriveRatingMax = $scoutCard->DriveRating;
                    $postGameDriveRatingMaxMatchIds[] = $match->toString();
                }

                $i++;

            }

            if($removeMin == 'false') {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $team->Id .'">' . $team->toString() . '</a>';
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
                $data[] = $postGameDefenseRatingMin;
                $data[] = $postGameOffenseRatingMin;
                $data[] = $postGameDriveRatingMin;

                $data['autoExitHabitatMinMatchIds'] = $autoExitHabitatMinMatchIds;
                $data['autoHatchPanelsMinMatchIds'] = $autoHatchPanelsMinMatchIds;
                $data['autoHatchPanelsAttemptsMinMatchIds'] = $autoHatchPanelsAttemptsMinMatchIds;
                $data['autoCargoStoredMinMatchIds'] = $autoCargoStoredMinMatchIds;
                $data['autoCargoStoredAttemptsMinMatchIds'] = $autoCargoStoredAttemptsMinMatchIds;
                $data['teleopHatchPanelsMinMatchIds'] = $teleopHatchPanelsMinMatchIds;
                $data['teleopHatchPanelsAttemptsMinMatchIds'] = $teleopHatchPanelsAttemptsMinMatchIds;
                $data['teleopCargoStoredMinMatchIds'] = $teleopCargoStoredMinMatchIds;
                $data['teleopCargoStoredAttemptsMinMatchIds'] = $teleopCargoStoredAttemptsMinMatchIds;
                $data['endGameReturnedToHabitatMinMatchIds'] = $endGameReturnedToHabitatMinMatchIds;
                $data['endGameReturnedToHabitatAttemptsMinMatchIds'] = $endGameReturnedToHabitatAttemptsMinMatchIds;
                $data['postGameDefenseRatingMinMatchIds'] = $postGameDefenseRatingMinMatchIds;
                $data['postGameOffenseRatingMinMatchIds'] = $postGameOffenseRatingMinMatchIds;
                $data['postGameDriveRatingMinMatchIds'] = $postGameDriveRatingMinMatchIds;

                $return_array[] = $data;
            }


            if($removeAvg == 'false') {
                $scoutCardCount = ($i == 0) ? 1 : $i;

                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $team->Id .'">' . $team->toString() . '</a>';
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
                $data[] = ($scoutCardCount - $nulledDefenseRatings) == 0 ? 0 : round($postGameDefenseRating / ($scoutCardCount - $nulledDefenseRatings), 2);
                $data[] = ($scoutCardCount - $nulledOffenseRatings) == 0 ? 0 : round($postGameOffenseRating / ($scoutCardCount - $nulledOffenseRatings), 2);
                $data[] = round($postGameDriveRating / $scoutCardCount, 2);
                
                $return_array[] = $data;
            }

            if($removeMax == 'false') {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $team->Id .'">' . $team->toString() . '</a>';
                $data[] = 'MAX';
                $data[] = $autoExitHabitatMax == 0 ? 'No' : 'Level ' . $autoExitHabitatMax;
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
                $data[] = $postGameDefenseRatingMax;
                $data[] = $postGameOffenseRatingMax;
                $data[] = $postGameDriveRatingMax;

                $data['autoExitHabitatMaxMatchIds'] = $autoExitHabitatMaxMatchIds;
                $data['autoHatchPanelsMaxMatchIds'] = $autoHatchPanelsMaxMatchIds;
                $data['autoHatchPanelsAttemptsMaxMatchIds'] = $autoHatchPanelsAttemptsMaxMatchIds;
                $data['autoCargoStoredMaxMatchIds'] = $autoCargoStoredMaxMatchIds;
                $data['autoCargoStoredAttemptsMaxMatchIds'] = $autoCargoStoredAttemptsMaxMatchIds;
                $data['teleopHatchPanelsMaxMatchIds'] = $teleopHatchPanelsMaxMatchIds;
                $data['teleopHatchPanelsAttemptsMaxMatchIds'] = $teleopHatchPanelsAttemptsMaxMatchIds;
                $data['teleopCargoStoredMaxMatchIds'] = $teleopCargoStoredMaxMatchIds;
                $data['teleopCargoStoredAttemptsMaxMatchIds'] = $teleopCargoStoredAttemptsMaxMatchIds;
                $data['endGameReturnedToHabitatMaxMatchIds'] = $endGameReturnedToHabitatMaxMatchIds;
                $data['endGameReturnedToHabitatAttemptsMaxMatchIds'] = $endGameReturnedToHabitatAttemptsMaxMatchIds;
                $data['postGameDefenseRatingMaxMatchIds'] = $postGameDefenseRatingMaxMatchIds;
                $data['postGameOffenseRatingMaxMatchIds'] = $postGameOffenseRatingMaxMatchIds;
                $data['postGameDriveRatingMaxMatchIds'] = $postGameDriveRatingMaxMatchIds;

                $return_array[] = $data;
            }

        }

        echo json_encode(array(
            "data" => $return_array
        ));

        break;
}