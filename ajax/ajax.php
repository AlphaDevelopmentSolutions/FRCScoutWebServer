<?php
require_once("../config.php");
require_once(ROOT_DIR . "/classes/tables/Events.php");
require_once(ROOT_DIR . "/classes/tables/Teams.php");
require_once(ROOT_DIR . "/classes/tables/Matches.php");
switch ($_POST['action'])
{
    case 'load_stats':
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
                if ($scoutCardInfo->PropertyKeyId == $scoutCardInfoKeys[$i]->Id)
                    $scoutCardInfoKey = $scoutCardInfoKeys[$i];
                $i++;

            } while (empty($scoutCardInfoKey) && $i < count($scoutCardInfoKeys));

            $i = 0;//key matched, reset $i

            //if the current key we are on is specified as being included in the stats, generate stats
            if ($scoutCardInfoKey->IncludeInStats == 1)
            {
                //set the array key so we don't have to change it manually
                $arrayKey = $scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName;

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
                                        if ($scoutCardInfo->PropertyKeyId == $scoutCardInfoKeys[$i]->Id)
                                            $scoutCardInfoKey = $scoutCardInfoKeys[$i];
                                        $i++;

                                    } while (empty($scoutCardInfoKey) && $i < count($scoutCardInfoKeys));

                                    //check if the key is require to be included in stats
                                    if ($scoutCardInfoKey->IncludeInStats == 1)
                                    {
                                        //add records to match avgs
                                        $return_array['MatchAvgs'][$match->MatchNumber][$scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName] =
                                            ((empty($return_array['MatchAvgs'][$match->MatchNumber][$scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName])) ?
                                                $scoutCardInfo->PropertyValue
                                                :
                                                $return_array['MatchAvgs'][$match->MatchNumber][$scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName] + $scoutCardInfo->PropertyValue);

                                        //if key specifies to null zeros, add null record to array
                                        if ($scoutCardInfoKey->NullZeros == 1)
                                            if ($scoutCardInfo->PropertyValue == 0)
                                                $return_array['MatchAvgs'][$match->MatchNumber]['Nulled ' . $scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName] = ((empty($return_array['MatchAvgs'][$match->MatchNumber]['Nulled ' . $scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName])) ? 1 : $return_array[$scoutCardInfo->TeamId]['Nulled ' . $scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName] + 1);


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
}