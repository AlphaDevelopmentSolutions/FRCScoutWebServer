<?php
require_once("../config.php");
require_once(ROOT_DIR . "/classes/tables/Events.php");
require_once(ROOT_DIR . "/classes/tables/Teams.php");
require_once(ROOT_DIR . "/classes/tables/Matches.php");
switch ($_POST['action'])
{
    case 'load_new_stats':
        $return_array = array();

        require_once(ROOT_DIR . "/classes/tables/ScoutCardInfo.php");
        require_once(ROOT_DIR . "/classes/tables/ScoutCardInfoKeys.php");

        //grab items from post response
        $eventId = $_POST['eventId'];
        $teamIds = json_decode($_POST['teamIds']);
        $matchId = $_POST['matchId'];

        //init base vars
        $event = Events::withId($eventId);
        $teams = null;
        $match = null;

        //assign all the teams to the array
        if (!empty($teamIds))
            foreach ($teamIds as $teamId)
                $teams[] = Teams::withId($teamId);

        //assign the match if specified
        if (!empty($matchId))
            $match = Matches::withId($matchId);

        $scoutCardInfoKeys = ScoutCardInfoKeys::getObjects();//get all the scout card info keys from the database
        $scoutCardInfoKey = null; //set the current key to null
        $i = 0;//var to get the record at specified index
        $addRecord = false;//boolean if we should add the record to the list
        $eventScoutCardInfo = ScoutCardInfo::forTeam(null, $event, $match, null);//get all scout cards from the event / match
        $temp = array();//overwritten multiple times, placeholder for getting what teams were in a match

        //iterate through each scout card info at the event
        foreach ($eventScoutCardInfo as $scoutCardInfo)
        {
            //this holds a placeholder for each match a team was at
            //because the way arrays work, we just overwrite each time we hit the same record
            //with placeholder text
            //we later count how many teams were at each match (how many indexes are at a matchid)
            //to determine how many scout cards were made
            $temp[$scoutCardInfo->MatchId][$scoutCardInfo->TeamId] = 'placeholder';

            //while there is no current scout card info key selected
            //iterate through the current scout card info and match it up to the key
            do
            {
                //match the state & key
                if ($scoutCardInfo->PropertyState == $scoutCardInfoKeys[$i]->KeyState && $scoutCardInfo->PropertyKey == $scoutCardInfoKeys[$i]->KeyName)
                    $scoutCardInfoKey = $scoutCardInfoKeys[$i];
                $i++;

            } while (empty($scoutCardInfoKey) && $i < count($scoutCardInfoKeys));

            $i = 0;//key matched, reset $i

            //if the current key we are on is specified as being included in the stats, generate stats
            if ($scoutCardInfoKey->IncludeInStats == 1)
            {
                //set the array key so we don't have to change it manually
                $arrayKey = $scoutCardInfo->PropertyState . ' ' . $scoutCardInfo->PropertyKey;

                //add to the event avgs
                $return_array['EventAvg'][$arrayKey] =
                    ((empty($return_array['EventAvg'][$arrayKey])) ?
                        $scoutCardInfo->PropertyValue
                        :
                        $return_array['EventAvg'][$arrayKey] + $scoutCardInfo->PropertyValue);

                //if the current scout card info indicates to null 0's
                //add a null zero count to the index
                if ($scoutCardInfoKey->NullZeros == 1)
                    if ($scoutCardInfo->PropertyValue == 0)
                        $return_array['EventAvg']['Nulled ' . $arrayKey] =
                            ((empty($return_array['EventAvg']['Nulled ' . $arrayKey])) ?
                                1
                                :
                                $return_array['EventAvg']['Nulled ' . $arrayKey] + 1);

                //if teams were specified, filter out all the teams that were not specified
                if (!empty($teams))
                {
                    //iterate through the teams and check if the scout card info team matches the specified team
                    foreach ($teams as $team)
                        if ($team->Id == $scoutCardInfo->TeamId)
                            $addRecord = true;
                }

                //otherwise just add the record
                else
                    $addRecord = true;

                //if addRecord was changed from false to true
                //add the record
                if ($addRecord)
                {
                    //specifying 1 team indicates a match breakdown
                    //as long as teams is not 1, give event breakdown
                    if (count($teams) != 1)
                    {
                        //add the value of the scoutcardinfo to the array
                        $return_array[$scoutCardInfo->TeamId][$arrayKey] =
                            ((empty($return_array[$scoutCardInfo->TeamId][$arrayKey])) ?
                                $scoutCardInfo->PropertyValue
                                :
                                $return_array[$scoutCardInfo->TeamId][$arrayKey] + $scoutCardInfo->PropertyValue);

                        //if the current scout card info indicates to null 0's
                        //add a null zero count to the index
                        if ($scoutCardInfoKey->NullZeros == 1)
                            if ($scoutCardInfo->PropertyValue == 0)
                                $return_array[$scoutCardInfo->TeamId]['Nulled ' . $arrayKey] =
                                    ((empty($return_array[$scoutCardInfo->TeamId]['Nulled ' . $arrayKey])) ?
                                        1
                                        :
                                        $return_array[$scoutCardInfo->TeamId]['Nulled ' . $arrayKey] + 1);

                    }

                    //1 team was specified, the array struct changes a bit
                    else
                    {
                        //add the value of the scoutcardinfo to the array
                        $return_array[$scoutCardInfo->TeamId][$scoutCardInfo->MatchId][$arrayKey] =
                            ((empty($return_array[$scoutCardInfo->TeamId][$scoutCardInfo->MatchId][$arrayKey])) ?
                                $scoutCardInfo->PropertyValue
                                :
                                $return_array[$scoutCardInfo->TeamId][$scoutCardInfo->MatchId][$arrayKey] + $scoutCardInfo->PropertyValue);

                    }


                }
            }

            $scoutCardInfoKey = null;//reset the key
            $addRecord = false;//reset the addRecord

        }

        //this will count how many scout cards were generated at an event based on the array
        $eventScoutCardCount = 0;
        foreach ($temp as $matchRecord)//iterate through each matchRecord in the temp array and count how many team records are in it
            $eventScoutCardCount += count($matchRecord);
        $return_array['EventAvg']['CardCount'] = $eventScoutCardCount;

        //if a match was not specified, give an event avg and event breakdown
        if (empty($match))
        {
            //not match breakdown, calculate all averages
            if (count($teams) != 1)
            {
                //iterate through each team in the array
                foreach ($return_array as $teamKey => $statKey)
                {
                    //do not calculate averages for the event average yet
                    if($teamKey != 'EventAvg')
                    {
                        $cardCount = 0;

                        //iterate through each match record inside the temp array
                        foreach($temp as $tempKey => $tempVal)
                            //iterate through each team inside the match record
                            foreach($tempVal as $tempValKey => $temValVal)
                                //if the key of the team is the same as the team we are generating stats for, add to the card count
                                if($tempValKey == $teamKey)
                                    $cardCount++;

                        //iterate through each stat inside the return array and calculate averages
                        foreach ($statKey as $key => $statValue)
                        {
                            //do not modify nulled stored records
                            if (strpos($key, 'Nulled') === false)
                            {
                                $tempCardCount = $cardCount;

                                //check if a nulled key exists inside the array at the current record
                                if (!empty($return_array[$teamKey]['Nulled ' . $key]))
                                    $tempCardCount = $cardCount - $return_array[$teamKey]['Nulled ' . $key];//if it does, modify the card count to remove nulled records

                                $return_array[$teamKey][$key] = (($statValue != 0) ? round($statValue / $tempCardCount, 2) : 0);//calculate average

                                unset($return_array[$teamKey]['Nulled ' . $key]);//remove nulled record from array

                            }
                        }

                        $cardCount = 0;//reset card count
                    }
                }

                $temp = array();//reset temp array
            }


            //1 Team specified, match breakdown
            else
            {
                //reset the array from starting
                $temp = array();

                //declare starting vars
                $currMatch = null;
                $currTeam = null;

                //iterate through each item in the return array
                foreach ($return_array as $teamId => $matches)
                {
                    //don't touch event avgs
                    if ($teamId != 'EventAvg')
                    {
                        //iterate through each stat inside each match
                        foreach ($matches as $matchId => $statKey)
                        {
                            //get the match from the database
                            $match = Matches::withId($matchId);

                            //iterate through each of the scout card infos pulled from event
                            foreach ($eventScoutCardInfo as $scoutCardInfo)
                            {
                                //if the current scout card info matches the current match key
                                //calculate match average
                                if($scoutCardInfo->MatchId == $match->Key)
                                {
                                    //match the info to the info key
                                    do
                                    {
                                        //match state and key name
                                        if ($scoutCardInfo->PropertyState == $scoutCardInfoKeys[$i]->KeyState && $scoutCardInfo->PropertyKey == $scoutCardInfoKeys[$i]->KeyName)
                                            $scoutCardInfoKey = $scoutCardInfoKeys[$i];
                                        $i++;

                                    } while (empty($scoutCardInfoKey) && $i < count($scoutCardInfoKeys));

                                    //check if the key is require to be included in stats
                                    if ($scoutCardInfoKey->IncludeInStats == 1)
                                    {
                                        //add records to match avgs
                                        $return_array['MatchAvgs'][$match->MatchNumber][$scoutCardInfo->PropertyState . ' ' . $scoutCardInfo->PropertyKey] =
                                            ((empty($return_array['MatchAvgs'][$match->MatchNumber][$scoutCardInfo->PropertyState . ' ' . $scoutCardInfo->PropertyKey])) ?
                                                $scoutCardInfo->PropertyValue
                                                :
                                                $return_array['MatchAvgs'][$match->MatchNumber][$scoutCardInfo->PropertyState . ' ' . $scoutCardInfo->PropertyKey] + $scoutCardInfo->PropertyValue);

                                        //if key specifies to null zeros, add null record to array
                                        if ($scoutCardInfoKey->NullZeros == 1)
                                            if ($scoutCardInfo->PropertyValue == 0)
                                                $return_array['MatchAvgs'][$match->MatchNumber]['Nulled ' . $scoutCardInfo->PropertyState . ' ' . $scoutCardInfo->PropertyKey] = ((empty($return_array['MatchAvgs'][$match->MatchNumber]['Nulled ' . $scoutCardInfo->PropertyState . ' ' . $scoutCardInfo->PropertyKey])) ? 1 : $return_array[$scoutCardInfo->TeamId]['Nulled ' . $scoutCardInfo->PropertyState . ' ' . $scoutCardInfo->PropertyKey] + 1);


                                    }

                                    $scoutCardInfoKey = null;//reset key
                                    $i = 0;//reset index

                                    $temp[$match->MatchNumber][$scoutCardInfo->TeamId] = 'placeholder';//add team placeholder
                                }

                            }

                            //iterate through each record inside the temp array to set card counts
                            foreach($temp as $key => $value)
                                $return_array['MatchAvgs'][$match->MatchNumber]['CardCount'] = count($value);

                            $return_array[$teamId][$match->MatchNumber] = $return_array[$teamId][$matchId];//rename all records with match numbers to match ids
                            unset($return_array[$teamId][$matchId]);//remove transferred records
                        }
                    }
                }

                //reset temp array
                $temp = array();

                //iterate through each match avg record to calc averages
                foreach ($return_array['MatchAvgs'] as $matchId => $statKey)
                {
                    //iterate through each stat inside the match avgs
                    foreach ($statKey as $key => $statValue)
                    {
                        //don't touch null and card count records
                        if (strpos($key, 'Nulled') === false && strpos($key, 'CardCount') === false)
                        {
                            //get the card count
                            $tempCardCount = $return_array['MatchAvgs'][$matchId]['CardCount'];

                            //modify the card count if a null count record exists
                            if (!empty($return_array['MatchAvgs'][$matchId]['Nulled ' . $key]))
                                $tempCardCount = $tempCardCount - $return_array['MatchAvgs'][$matchId]['Nulled ' . $key];

                            //calc average
                            $return_array['MatchAvgs'][$matchId][$key] = round($statValue / $tempCardCount, 2);

                            //remove null record count from array
                            unset($return_array['MatchAvgs'][$matchId]['Nulled ' . $key]);
                        }
                    }

                    //remove card count once match avgs generated
                    unset($return_array['MatchAvgs'][$matchId]['CardCount']);
                }
            }
        }

        //match was specified, majority of the work does not have to be done
        //only thing needed to be added is the color of each alliance member
        else
        {
            foreach ($return_array as $teamId => $statKey)
            {
                $return_array[$teamId]['Alliance Color'] = $match->getAllianceColor(Teams::withId($teamId));


            }
        }

        //for each record inside of the event array, we need to calculate the average based on the number of scout cards
        foreach ($return_array['EventAvg'] as $key => $statValue)
        {
            //don't touch null and card count records
            if (strpos($key, 'Nulled') === false && strpos($key, 'CardCount') === false)
            {
                //get the card count
                $tempCardCount = $return_array['EventAvg']['CardCount'];

                //modify the card count if a null count record exists
                if (!empty($return_array['EventAvg']['Nulled ' . $key]))
                    $tempCardCount = $tempCardCount - $return_array['EventAvg']['Nulled ' . $key];

                //calc average
                $return_array['EventAvg'][$key] = (($statValue != 0) ? round($statValue / $tempCardCount, 2) : 0);

                //remove null record count from array
                unset($return_array['EventAvg']['Nulled ' . $key]);
            }
        }

        unset($return_array['EventAvg']['CardCount']);//remove the card count record from the array


        //sort the array by the team id that shows up
        if (count($teams) != 1)
            ksort($return_array);

        //if only 1 team was specified (match breakdown)
        //sort the array by the match number
        else
        {
            $matchRecords = $return_array[$teams[0]];//get all the match records from the array

            ksort($return_array[$teams[0]], SORT_NUMERIC);//sort by the match number

            unset($return_array[$teams[0]]);//remove all the match records from the array

            $return_array[$teams[0]] = $matchRecords;//re-add the sorted array of matches
        }


        echo json_encode($return_array);

        break;

    case 'load_stats_legacy':

        require_once('../classes/tables/Teams.php');
        require_once('../classes/tables/ScoutCards.php');
        require_once('../classes/tables/Matches.php');
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
                if ($scoutCard->AutonomousExitHabitat == 1)
                {
                    if ($scoutCard->PreGameStartingLevel <= $autoExitHabitatMin)
                    {
                        $autoExitHabitatMinMatchIds = (($scoutCard->PreGameStartingLevel < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                        $autoExitHabitatMin = $scoutCard->PreGameStartingLevel;
                        $autoExitHabitatMinMatchIds[] = $match->toString();
                    }
                } else if (0 <= $autoExitHabitatMin)
                {
                    $autoExitHabitatMinMatchIds = ((0 < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                    $autoExitHabitatMin = 0;
                    $autoExitHabitatMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->AutonomousHatchPanelsSecured <= $autoHatchPanelsMin)
                {
                    $autoHatchPanelsMinMatchIds = (($scoutCard->AutonomousHatchPanelsSecured < $autoHatchPanelsMin) ? array() : $autoHatchPanelsMinMatchIds);
                    $autoHatchPanelsMin = $scoutCard->AutonomousHatchPanelsSecured;
                    $autoHatchPanelsMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->AutonomousCargoStored <= $autoCargoStoredMin)
                {
                    $autoCargoStoredMinMatchIds = (($scoutCard->AutonomousCargoStored < $autoCargoStoredMin) ? array() : $autoCargoStoredMinMatchIds);
                    $autoCargoStoredMin = $scoutCard->AutonomousCargoStored;
                    $autoCargoStoredMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->TeleopHatchPanelsSecured <= $teleopHatchPanelsMin)
                {
                    $teleopHatchPanelsMinMatchIds = (($scoutCard->TeleopHatchPanelsSecured < $teleopHatchPanelsMin) ? array() : $teleopHatchPanelsMinMatchIds);
                    $teleopHatchPanelsMin = $scoutCard->TeleopHatchPanelsSecured;
                    $teleopHatchPanelsMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->TeleopCargoStored <= $teleopCargoStoredMin)
                {
                    $teleopCargoStoredMinMatchIds = (($scoutCard->TeleopCargoStored < $teleopCargoStoredMin) ? array() : $teleopCargoStoredMinMatchIds);
                    $teleopCargoStoredMin = $scoutCard->TeleopCargoStored;
                    $teleopCargoStoredMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->AutonomousHatchPanelsSecuredAttempts <= $autoHatchPanelsAttemptsMin)
                {
                    $autoHatchPanelsAttemptsMinMatchIds = (($scoutCard->AutonomousHatchPanelsSecuredAttempts < $autoHatchPanelsAttemptsMin) ? array() : $autoHatchPanelsAttemptsMinMatchIds);
                    $autoHatchPanelsAttemptsMin = $scoutCard->AutonomousHatchPanelsSecuredAttempts;
                    $autoHatchPanelsAttemptsMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->AutonomousCargoStoredAttempts <= $autoCargoStoredAttemptsMin)
                {
                    $autoCargoStoredAttemptsMinMatchIds = (($scoutCard->AutonomousCargoStoredAttempts < $autoCargoStoredAttemptsMin) ? array() : $autoCargoStoredAttemptsMinMatchIds);
                    $autoCargoStoredAttemptsMin = $scoutCard->AutonomousCargoStoredAttempts;
                    $autoCargoStoredAttemptsMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->TeleopHatchPanelsSecuredAttempts <= $teleopHatchPanelsAttemptsMin)
                {
                    $teleopHatchPanelsAttemptsMinMatchIds = (($scoutCard->TeleopHatchPanelsSecuredAttempts < $teleopHatchPanelsAttemptsMin) ? array() : $teleopHatchPanelsAttemptsMinMatchIds);
                    $teleopHatchPanelsAttemptsMin = $scoutCard->TeleopHatchPanelsSecuredAttempts;
                    $teleopHatchPanelsAttemptsMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->TeleopCargoStoredAttempts <= $teleopCargoStoredAttemptsMin)
                {
                    $teleopCargoStoredAttemptsMinMatchIds = (($scoutCard->TeleopCargoStoredAttempts < $teleopCargoStoredAttemptsMin) ? array() : $teleopCargoStoredAttemptsMinMatchIds);
                    $teleopCargoStoredAttemptsMin = $scoutCard->TeleopCargoStoredAttempts;
                    $teleopCargoStoredAttemptsMinMatchIds[] = $match->toString();
                }

                if (empty($scoutCard->EndGameReturnedToHabitat))
                {
                    if (0 <= $endGameReturnedToHabitatMin)
                    {
                        $endGameReturnedToHabitatMinMatchIds = (($scoutCard->EndGameReturnedToHabitat < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                        $endGameReturnedToHabitatMin = 0;
                        $endGameReturnedToHabitatMinMatchIds[] = $match->toString();
                    }
                } else if ($scoutCard->EndGameReturnedToHabitat <= $endGameReturnedToHabitatMin)
                {
                    $endGameReturnedToHabitatMinMatchIds = (($scoutCard->EndGameReturnedToHabitat < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                    $endGameReturnedToHabitatMin = $scoutCard->EndGameReturnedToHabitat;
                    $endGameReturnedToHabitatMinMatchIds[] = $match->toString();
                }

                if (empty($scoutCard->EndGameReturnedToHabitatAttempts))
                {
                    if (0 <= $endGameReturnedToHabitatAttemptsMin)
                    {
                        $endGameReturnedToHabitatAttemptsMinMatchIds = (($scoutCard->EndGameReturnedToHabitatAttempts < $endGameReturnedToHabitatAttemptsMin) ? array() : $endGameReturnedToHabitatAttemptsMinMatchIds);
                        $endGameReturnedToHabitatAttemptsMin = 0;
                        $endGameReturnedToHabitatAttemptsMinMatchIds[] = $match->toString();
                    }
                } else if ($scoutCard->EndGameReturnedToHabitatAttempts <= $endGameReturnedToHabitatAttemptsMin)
                {
                    $endGameReturnedToHabitatAttemptsMinMatchIds = (($scoutCard->EndGameReturnedToHabitatAttempts < $endGameReturnedToHabitatAttemptsMin) ? array() : $endGameReturnedToHabitatAttemptsMinMatchIds);
                    $endGameReturnedToHabitatAttemptsMin = $scoutCard->EndGameReturnedToHabitatAttempts;
                    $endGameReturnedToHabitatAttemptsMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->DefenseRating <= $postGameDefenseRatingMin && $scoutCard->DefenseRating != 0)
                {
                    $postGameDefenseRatingMinMatchIds = (($scoutCard->DefenseRating < $postGameDefenseRatingMin) ? array() : $postGameDefenseRatingMinMatchIds);
                    $postGameDefenseRatingMin = $scoutCard->DefenseRating;
                    $postGameDefenseRatingMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->OffenseRating <= $postGameOffenseRatingMin && $scoutCard->OffenseRating != 0)
                {
                    $postGameOffenseRatingMinMatchIds = (($scoutCard->OffenseRating < $postGameOffenseRatingMin) ? array() : $postGameOffenseRatingMinMatchIds);
                    $postGameOffenseRatingMin = $scoutCard->OffenseRating;
                    $postGameOffenseRatingMinMatchIds[] = $match->toString();
                }

                if ($scoutCard->DriveRating <= $postGameDriveRatingMin)
                {
                    $postGameDriveRatingMinMatchIds = (($scoutCard->DriveRating < $postGameDriveRatingMin) ? array() : $postGameDriveRatingMinMatchIds);
                    $postGameDriveRatingMin = $scoutCard->DriveRating;
                    $postGameDriveRatingMinMatchIds[] = $match->toString();
                }

                //calc avg
                if ($scoutCard->AutonomousExitHabitat == 1)
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

                if (!empty($scoutCard->EndGameReturnedToHabitat))
                    $endGameReturnedToHabitat += $scoutCard->EndGameReturnedToHabitat;

                if (!empty($scoutCard->EndGameReturnedToHabitatAttempts))
                    $endGameReturnedToHabitatAttempts += $scoutCard->EndGameReturnedToHabitatAttempts;


                //calc max
                if ($scoutCard->AutonomousExitHabitat == 1)
                {
                    if ($scoutCard->PreGameStartingLevel >= $autoExitHabitatMax)
                    {
                        $autoExitHabitatMaxMatchIds = (($scoutCard->PreGameStartingLevel > $autoExitHabitatMax) ? array() : $autoExitHabitatMaxMatchIds);
                        $autoExitHabitatMax = $scoutCard->PreGameStartingLevel;
                        $autoExitHabitatMaxMatchIds[] = $match->toString();
                    }
                } else if (0 >= $autoExitHabitatMax)
                {
                    $autoExitHabitatMaxMatchIds = ((0 > $autoExitHabitatMax) ? array() : $autoExitHabitatMaxMatchIds);
                    $autoExitHabitatMax = 0;
                    $autoExitHabitatMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->AutonomousHatchPanelsSecured >= $autoHatchPanelsMax)
                {
                    $autoHatchPanelsMaxMatchIds = (($scoutCard->AutonomousHatchPanelsSecured > $autoHatchPanelsMax) ? array() : $autoHatchPanelsMaxMatchIds);
                    $autoHatchPanelsMax = $scoutCard->AutonomousHatchPanelsSecured;
                    $autoHatchPanelsMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->AutonomousCargoStored >= $autoCargoStoredMax)
                {
                    $autoCargoStoredMaxMatchIds = (($scoutCard->AutonomousCargoStored > $autoCargoStoredMax) ? array() : $autoCargoStoredMaxMatchIds);
                    $autoCargoStoredMax = $scoutCard->AutonomousCargoStored;
                    $autoCargoStoredMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->TeleopHatchPanelsSecured >= $teleopHatchPanelsMax)
                {
                    $teleopHatchPanelsMaxMatchIds = (($scoutCard->TeleopHatchPanelsSecured > $teleopHatchPanelsMax) ? array() : $teleopHatchPanelsMaxMatchIds);
                    $teleopHatchPanelsMax = $scoutCard->TeleopHatchPanelsSecured;
                    $teleopHatchPanelsMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->TeleopCargoStored >= $teleopCargoStoredMax)
                {
                    $teleopCargoStoredMaxMatchIds = (($scoutCard->TeleopCargoStored > $teleopCargoStoredMax) ? array() : $teleopCargoStoredMaxMatchIds);
                    $teleopCargoStoredMax = $scoutCard->TeleopCargoStored;
                    $teleopCargoStoredMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->AutonomousHatchPanelsSecuredAttempts >= $autoHatchPanelsAttemptsMax)
                {
                    $autoHatchPanelsAttemptsMaxMatchIds = (($scoutCard->AutonomousHatchPanelsSecuredAttempts > $autoHatchPanelsAttemptsMax) ? array() : $autoHatchPanelsAttemptsMaxMatchIds);
                    $autoHatchPanelsAttemptsMax = $scoutCard->AutonomousHatchPanelsSecuredAttempts;
                    $autoHatchPanelsAttemptsMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->AutonomousCargoStoredAttempts >= $autoCargoStoredAttemptsMax)
                {
                    $autoCargoStoredAttemptsMaxMatchIds = (($scoutCard->AutonomousCargoStoredAttempts > $autoCargoStoredAttemptsMax) ? array() : $autoCargoStoredAttemptsMaxMatchIds);
                    $autoCargoStoredAttemptsMax = $scoutCard->AutonomousCargoStoredAttempts;
                    $autoCargoStoredAttemptsMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->TeleopHatchPanelsSecuredAttempts >= $teleopHatchPanelsAttemptsMax)
                {
                    $teleopHatchPanelsAttemptsMaxMatchIds = (($scoutCard->TeleopHatchPanelsSecuredAttempts > $teleopHatchPanelsAttemptsMax) ? array() : $teleopHatchPanelsAttemptsMaxMatchIds);
                    $teleopHatchPanelsAttemptsMax = $scoutCard->TeleopHatchPanelsSecuredAttempts;
                    $teleopHatchPanelsAttemptsMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->TeleopCargoStoredAttempts >= $teleopCargoStoredAttemptsMax)
                {
                    $teleopCargoStoredAttemptsMaxMatchIds = (($scoutCard->TeleopCargoStoredAttempts > $teleopCargoStoredAttemptsMax) ? array() : $teleopCargoStoredAttemptsMaxMatchIds);
                    $teleopCargoStoredAttemptsMax = $scoutCard->TeleopCargoStoredAttempts;
                    $teleopCargoStoredAttemptsMaxMatchIds[] = $match->toString();
                }

                if (empty($scoutCard->EndGameReturnedToHabitat))
                {
                    if (0 >= $endGameReturnedToHabitatMax)
                    {
                        $endGameReturnedToHabitatMaxMatchIds = (($scoutCard->EndGameReturnedToHabitat > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                        $endGameReturnedToHabitatMax = 0;
                        $endGameReturnedToHabitatMaxMatchIds[] = $match->toString();
                    }
                } else if ($scoutCard->EndGameReturnedToHabitat >= $endGameReturnedToHabitatMax)
                {
                    $endGameReturnedToHabitatMaxMatchIds = (($scoutCard->EndGameReturnedToHabitat > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                    $endGameReturnedToHabitatMax = $scoutCard->EndGameReturnedToHabitat;
                    $endGameReturnedToHabitatMaxMatchIds[] = $match->toString();
                }

                if (empty($scoutCard->EndGameReturnedToHabitatAttempts))
                {
                    if (0 >= $endGameReturnedToHabitatAttemptsMax)
                    {
                        $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard->EndGameReturnedToHabitatAttempts > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                        $endGameReturnedToHabitatAttemptsMax = 0;
                        $endGameReturnedToHabitatAttemptsMaxMatchIds[] = $match->toString();
                    }
                } else if ($scoutCard->EndGameReturnedToHabitatAttempts >= $endGameReturnedToHabitatAttemptsMax)
                {
                    $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard->EndGameReturnedToHabitatAttempts > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                    $endGameReturnedToHabitatAttemptsMax = $scoutCard->EndGameReturnedToHabitatAttempts;
                    $endGameReturnedToHabitatAttemptsMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->DefenseRating >= $postGameDefenseRatingMax && $scoutCard->DefenseRating != 0)
                {
                    $postGameDefenseRatingMaxMatchIds = (($scoutCard->DefenseRating < $postGameDefenseRatingMax) ? array() : $postGameDefenseRatingMaxMatchIds);
                    $postGameDefenseRatingMax = $scoutCard->DefenseRating;
                    $postGameDefenseRatingMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->OffenseRating >= $postGameOffenseRatingMax && $scoutCard->OffenseRating != 0)
                {
                    $postGameOffenseRatingMaxMatchIds = (($scoutCard->OffenseRating < $postGameOffenseRatingMax) ? array() : $postGameOffenseRatingMaxMatchIds);
                    $postGameOffenseRatingMax = $scoutCard->OffenseRating;
                    $postGameOffenseRatingMaxMatchIds[] = $match->toString();
                }

                if ($scoutCard->DriveRating >= $postGameDriveRatingMax)
                {
                    $postGameDriveRatingMaxMatchIds = (($scoutCard->DriveRating < $postGameDriveRatingMax) ? array() : $postGameDriveRatingMaxMatchIds);
                    $postGameDriveRatingMax = $scoutCard->DriveRating;
                    $postGameDriveRatingMaxMatchIds[] = $match->toString();
                }

                $i++;

            }

            if ($removeMin == 'false')
            {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $team->Id . '">' . $team->toString() . '</a>';
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


            if ($removeAvg == 'false')
            {
                $scoutCardCount = ($i == 0) ? 1 : $i;

                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $team->Id . '">' . $team->toString() . '</a>';
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

            if ($removeMax == 'false')
            {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $team->Id . '">' . $team->toString() . '</a>';
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