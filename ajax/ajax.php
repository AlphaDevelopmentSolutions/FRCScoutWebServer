<?php
require_once("../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Matches.php");

switch ($_POST['action'])
{
    case 'load_stats':

        $statsArray = array();

        require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfo.php");
        require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");

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

        $calcSingleTeam = count($teams) == 1;
        $calcMultiTeam = count($teams) > 1 || empty($teams);
        $calcMatchTeams = !empty($match);

        $scoutCardInfoKeys = ScoutCardInfoKeys::getObjects();//get all the scout card info keys from the database
        $scoutCardInfos = ScoutCardInfo::forTeam(null, $event, null, null);//get all scout cards from the event / match
        $teamStatArray = array();

        if(!$calcSingleTeam)
        {
            foreach ($scoutCardInfos as $scoutCardInfo)
            {
                foreach ($scoutCardInfoKeys as $scoutCardInfoKey)
                {
                    if ($scoutCardInfoKey->Id == $scoutCardInfo->PropertyKeyId)
                    {
                        if ($scoutCardInfoKey->IncludeInStats == 1)
                        {
                            if ($scoutCardInfoKey->NullZeros == 0 || ($scoutCardInfoKey->NullZeros == 1 && $scoutCardInfo->PropertyValue != 0))
                            {
                                $teamStatArray[$scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName][$scoutCardInfo->TeamId][$scoutCardInfo->MatchId]['Cards'] = (!empty($teamStatArray[$scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName][$scoutCardInfo->TeamId][$scoutCardInfo->MatchId]['Cards'])) ? $teamStatArray[$scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName][$scoutCardInfo->TeamId][$scoutCardInfo->MatchId]['Cards'] + 1 : 1;
                                $teamStatArray[$scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName][$scoutCardInfo->TeamId][$scoutCardInfo->MatchId]['Values'] = (!empty($teamStatArray[$scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName][$scoutCardInfo->TeamId][$scoutCardInfo->MatchId]['Values'])) ? $teamStatArray[$scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName][$scoutCardInfo->TeamId][$scoutCardInfo->MatchId]['Values'] + $scoutCardInfo->PropertyValue : $scoutCardInfo->PropertyValue;
                            }
                        }

                        break;
                    }
                }
            }

            foreach ($teamStatArray as $scoutCardInfoKeyTitle => $teamArray)
            {
                $runningMatchTotal = 0;
                $runningMatchCardTotal = 0;

                foreach ($teamArray as $teamId => $matchArray)
                {
                    $runningInfoKeyTotal = 0;
                    $runningInfoKeyCardTotal = 0;

                    foreach($matchArray as $matchId => $valueCardArray)
                    {
                        foreach ($valueCardArray as $title => $value)
                        {
                            if($title == 'Values')
                                $runningInfoKeyTotal += $value;
                            else
                                $runningInfoKeyCardTotal += $value;
                        }
                    }

                    $runningMatchTotal += $runningInfoKeyTotal;
                    $runningMatchCardTotal += $runningInfoKeyCardTotal;

                    if($calcMatchTeams)
                    {
                        if($teamId == $match->BlueAllianceTeamOneId ||
                            $teamId == $match->BlueAllianceTeamTwoId ||
                            $teamId == $match->BlueAllianceTeamThreeId)
                        {
                            $statsArray[$teamId][$scoutCardInfoKeyTitle] = ((!empty($teamStatArray[$scoutCardInfoKeyTitle][$teamId][$match->Key]['Values'])) ? $teamStatArray[$scoutCardInfoKeyTitle][$teamId][$match->Key]['Values'] : 0);
                            $statsArray[$teamId]['Alliance Color'] = 'BLUE';
                        }


                        if($teamId == $match->RedAllianceTeamOneId ||
                            $teamId == $match->RedAllianceTeamTwoId ||
                            $teamId == $match->RedAllianceTeamThreeId)
                        {
                            $statsArray[$teamId][$scoutCardInfoKeyTitle] = ((!empty($teamStatArray[$scoutCardInfoKeyTitle][$teamId][$match->Key]['Values'])) ? $teamStatArray[$scoutCardInfoKeyTitle][$teamId][$match->Key]['Values'] : 0);
                            $statsArray[$teamId]['Alliance Color'] = 'RED';
                        }
                    }
                    else if($calcMultiTeam)
                    {
                        if(empty($teams))
                            $statsArray[$teamId][$scoutCardInfoKeyTitle] = round($runningInfoKeyTotal / $runningInfoKeyCardTotal, 2);

                        else if(in_array($teamId, $teamIds))
                            $statsArray[$teamId][$scoutCardInfoKeyTitle] = round($runningInfoKeyTotal / $runningInfoKeyCardTotal, 2);
                    }
                }

                $statsArray['EventAvg'][$scoutCardInfoKeyTitle] = round($runningMatchTotal / $runningMatchCardTotal, 2);
            }

            echo json_encode($statsArray);
        }

        //Single team, show per match breakdown
        else
        {
            $matches = $event->getMatches(null, $teams[0]);

            $teamStatArray = $teams[0]->getStats($matches, $scoutCardInfoKeys, $scoutCardInfos); //get team stats
            $eventStats = $event->getStats($scoutCardInfoKeys, $scoutCardInfos); //get event stats

            //get match stats
            $matchStatArray = array();
            foreach($matches as $iterationMatch)
                $matchStatArray[$iterationMatch->MatchNumber] = $iterationMatch->getStats($matches, $scoutCardInfoKeys, $scoutCardInfos);

            //add all stats to array
            $finalArray = array();
            $finalArray['MatchAvgs'] = $matchStatArray;
            $finalArray[$teams[0]->Id] = $teamStatArray;
            $finalArray['EventAvg'] = $eventStats;

            echo json_encode($finalArray);
        }

        break;
}
