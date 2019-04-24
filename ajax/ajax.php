<?php
require_once("../config.php");
switch($_POST['action'])
{
    case 'load_stats':

        require_once('../classes/Teams.php');
        require_once('../classes/ScoutCards.php');
        require_once('../classes/Matches.php');
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

            foreach (ScoutCards::getScoutCardsForTeam($team['Id'], $eventId) as $scoutCard)
            {

                $match = Matches::withKey($scoutCard['MatchId']);

                //calc min
                if($scoutCard['AutonomousExitHabitat'] == 1)
                {
                    if($scoutCard['PreGameStartingLevel']  <= $autoExitHabitatMin)
                    {
                        $autoExitHabitatMinMatchIds = (($scoutCard['PreGameStartingLevel'] < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                        $autoExitHabitatMin = $scoutCard['PreGameStartingLevel'];
                        $autoExitHabitatMinMatchIds[] = $match->toString();
                    }
                }
                else if(0 <= $autoExitHabitatMin)
                {
                    $autoExitHabitatMinMatchIds = ((0 < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                    $autoExitHabitatMin = 0;
                    $autoExitHabitatMinMatchIds[] = $match->toString();
                }

                if($scoutCard['AutonomousHatchPanelsSecured'] <= $autoHatchPanelsMin)
                {
                    $autoHatchPanelsMinMatchIds = (($scoutCard['AutonomousHatchPanelsSecured'] < $autoHatchPanelsMin) ? array() : $autoHatchPanelsMinMatchIds);
                    $autoHatchPanelsMin = $scoutCard['AutonomousHatchPanelsSecured'];
                    $autoHatchPanelsMinMatchIds[] = $match->toString();
                }

                if($scoutCard['AutonomousCargoStored'] <= $autoCargoStoredMin)
                {
                    $autoCargoStoredMinMatchIds = (($scoutCard['AutonomousCargoStored'] < $autoCargoStoredMin) ? array() : $autoCargoStoredMinMatchIds);
                    $autoCargoStoredMin = $scoutCard['AutonomousCargoStored'];
                    $autoCargoStoredMinMatchIds[] = $match->toString();
                }

                if($scoutCard['TeleopHatchPanelsSecured'] <= $teleopHatchPanelsMin)
                {
                    $teleopHatchPanelsMinMatchIds = (($scoutCard['TeleopHatchPanelsSecured'] < $teleopHatchPanelsMin) ? array() : $teleopHatchPanelsMinMatchIds);
                    $teleopHatchPanelsMin = $scoutCard['TeleopHatchPanelsSecured'];
                    $teleopHatchPanelsMinMatchIds[] = $match->toString();
                }

                if($scoutCard['TeleopCargoStored'] <= $teleopCargoStoredMin)
                {
                    $teleopCargoStoredMinMatchIds = (($scoutCard['TeleopCargoStored'] < $teleopCargoStoredMin) ? array() : $teleopCargoStoredMinMatchIds);
                    $teleopCargoStoredMin = $scoutCard['TeleopCargoStored'];
                    $teleopCargoStoredMinMatchIds[] = $match->toString();
                }

                if($scoutCard['AutonomousHatchPanelsSecuredAttempts'] <= $autoHatchPanelsAttemptsMin)
                {
                    $autoHatchPanelsAttemptsMinMatchIds = (($scoutCard['AutonomousHatchPanelsSecuredAttempts'] < $autoHatchPanelsAttemptsMin) ? array() : $autoHatchPanelsAttemptsMinMatchIds);
                    $autoHatchPanelsAttemptsMin = $scoutCard['AutonomousHatchPanelsSecuredAttempts'];
                    $autoHatchPanelsAttemptsMinMatchIds[] = $match->toString();
                }

                if($scoutCard['AutonomousCargoStoredAttempts'] <= $autoCargoStoredAttemptsMin)
                {
                    $autoCargoStoredAttemptsMinMatchIds = (($scoutCard['AutonomousCargoStoredAttempts'] < $autoCargoStoredAttemptsMin) ? array() : $autoCargoStoredAttemptsMinMatchIds);
                    $autoCargoStoredAttemptsMin = $scoutCard['AutonomousCargoStoredAttempts'];
                    $autoCargoStoredAttemptsMinMatchIds[] = $match->toString();
                }

                if($scoutCard['TeleopHatchPanelsSecuredAttempts'] <= $teleopHatchPanelsAttemptsMin)
                {
                    $teleopHatchPanelsAttemptsMinMatchIds = (($scoutCard['TeleopHatchPanelsSecuredAttempts'] < $teleopHatchPanelsAttemptsMin) ? array() : $teleopHatchPanelsAttemptsMinMatchIds);
                    $teleopHatchPanelsAttemptsMin = $scoutCard['TeleopHatchPanelsSecuredAttempts'];
                    $teleopHatchPanelsAttemptsMinMatchIds[] = $match->toString();
                }

                if($scoutCard['TeleopCargoStoredAttempts'] <= $teleopCargoStoredAttemptsMin)
                {
                    $teleopCargoStoredAttemptsMinMatchIds = (($scoutCard['TeleopCargoStoredAttempts'] < $teleopCargoStoredAttemptsMin) ? array() : $teleopCargoStoredAttemptsMinMatchIds);
                    $teleopCargoStoredAttemptsMin = $scoutCard['TeleopCargoStoredAttempts'];
                    $teleopCargoStoredAttemptsMinMatchIds[] = $match->toString();
                }

                if(empty($scoutCard['EndGameReturnedToHabitat']))
                {
                    if (0 <= $endGameReturnedToHabitatMin) {
                        $endGameReturnedToHabitatMinMatchIds = (($scoutCard['EndGameReturnedToHabitat'] < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                        $endGameReturnedToHabitatMin = 0;
                        $endGameReturnedToHabitatMinMatchIds[] = $match->toString();
                    }
                }
                else if($scoutCard['EndGameReturnedToHabitat'] <= $endGameReturnedToHabitatMin)
                {
                    $endGameReturnedToHabitatMinMatchIds = (($scoutCard['EndGameReturnedToHabitat'] < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
                    $endGameReturnedToHabitatMin = $scoutCard['EndGameReturnedToHabitat'];
                    $endGameReturnedToHabitatMinMatchIds[] = $match->toString();
                }

                if(empty($scoutCard['EndGameReturnedToHabitatAttempts'])) {
                    if (0 <= $endGameReturnedToHabitatAttemptsMin) {
                        $endGameReturnedToHabitatAttemptsMinMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] < $endGameReturnedToHabitatAttemptsMin) ? array() : $endGameReturnedToHabitatAttemptsMinMatchIds);
                        $endGameReturnedToHabitatAttemptsMin = 0;
                        $endGameReturnedToHabitatAttemptsMinMatchIds[] = $match->toString();
                    }
                }
                else if($scoutCard['EndGameReturnedToHabitatAttempts'] <= $endGameReturnedToHabitatAttemptsMin)
                {
                    $endGameReturnedToHabitatAttemptsMinMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] < $endGameReturnedToHabitatAttemptsMin) ? array() : $endGameReturnedToHabitatAttemptsMinMatchIds);
                    $endGameReturnedToHabitatAttemptsMin = $scoutCard['EndGameReturnedToHabitatAttempts'];
                    $endGameReturnedToHabitatAttemptsMinMatchIds[] = $match->toString();
                }

                if($scoutCard['DefenseRating'] <= $postGameDefenseRatingMin && $scoutCard['DefenseRating'] != 0)
                {
                    $postGameDefenseRatingMinMatchIds = (($scoutCard['DefenseRating'] < $postGameDefenseRatingMin) ? array() : $postGameDefenseRatingMinMatchIds);
                    $postGameDefenseRatingMin = $scoutCard['DefenseRating'];
                    $postGameDefenseRatingMinMatchIds[] = $match->toString();
                }

                if($scoutCard['OffenseRating'] <= $postGameOffenseRatingMin && $scoutCard['OffenseRating'] != 0)
                {
                    $postGameOffenseRatingMinMatchIds = (($scoutCard['OffenseRating'] < $postGameOffenseRatingMin) ? array() : $postGameOffenseRatingMinMatchIds);
                    $postGameOffenseRatingMin = $scoutCard['OffenseRating'];
                    $postGameOffenseRatingMinMatchIds[] = $match->toString();
                }

                if($scoutCard['DriveRating'] <= $postGameDriveRatingMin)
                {
                    $postGameDriveRatingMinMatchIds = (($scoutCard['DriveRating'] < $postGameDriveRatingMin) ? array() : $postGameDriveRatingMinMatchIds);
                    $postGameDriveRatingMin = $scoutCard['DriveRating'];
                    $postGameDriveRatingMinMatchIds[] = $match->toString();
                }

                //calc avg
                if($scoutCard['AutonomousExitHabitat'] == 1)
                    $autoExitHabitat += $scoutCard['PreGameStartingLevel'];

                $autoHatchPanels += $scoutCard['AutonomousHatchPanelsSecured'];
                $autoHatchPanelsAttempts += $scoutCard['AutonomousHatchPanelsSecuredAttempts'];
                $autoCargoStored += $scoutCard['AutonomousCargoStored'];
                $autoCargoStoredAttempts += $scoutCard['AutonomousCargoStoredAttempts'];
                
                $teleopHatchPanels += $scoutCard['TeleopHatchPanelsSecured'];
                $teleopHatchPanelsAttempts += $scoutCard['TeleopHatchPanelsSecuredAttempts'];
                $teleopCargoStored += $scoutCard['TeleopCargoStored'];
                $teleopCargoStoredAttempts += $scoutCard['TeleopCargoStoredAttempts'];
                
                $postGameDefenseRating += $scoutCard['DefenseRating'];
                $postGameOffenseRating += $scoutCard['OffenseRating'];
                $postGameDriveRating += $scoutCard['DriveRating'];
                
                $nulledDefenseRatings = $scoutCard['DefenseRating'] == 0 ? $nulledDefenseRatings + 1 : $nulledDefenseRatings;
                $nulledOffenseRatings = $scoutCard['OffenseRating'] == 0 ? $nulledOffenseRatings + 1 : $nulledOffenseRatings;

                if(!empty($scoutCard['EndGameReturnedToHabitat']))
                    $endGameReturnedToHabitat += $scoutCard['EndGameReturnedToHabitat'];

                if(!empty($scoutCard['EndGameReturnedToHabitatAttempts']))
                    $endGameReturnedToHabitatAttempts += $scoutCard['EndGameReturnedToHabitatAttempts'];


                //calc max
                if($scoutCard['AutonomousExitHabitat'] == 1)
                {
                    if($scoutCard['PreGameStartingLevel']  >= $autoExitHabitatMax)
                    {
                        $autoExitHabitatMaxMatchIds = (($scoutCard['PreGameStartingLevel'] > $autoExitHabitatMax) ? array() : $autoExitHabitatMaxMatchIds);
                        $autoExitHabitatMax = $scoutCard['PreGameStartingLevel'];
                        $autoExitHabitatMaxMatchIds[] = $match->toString();
                    }
                }
                else if(0 >= $autoExitHabitatMax)
                {
                    $autoExitHabitatMaxMatchIds = ((0 > $autoExitHabitatMax) ? array() : $autoExitHabitatMaxMatchIds);
                    $autoExitHabitatMax = 0;
                    $autoExitHabitatMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['AutonomousHatchPanelsSecured'] >= $autoHatchPanelsMax)
                {
                    $autoHatchPanelsMaxMatchIds = (($scoutCard['AutonomousHatchPanelsSecured'] > $autoHatchPanelsMax) ? array() : $autoHatchPanelsMaxMatchIds);
                    $autoHatchPanelsMax = $scoutCard['AutonomousHatchPanelsSecured'];
                    $autoHatchPanelsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['AutonomousCargoStored'] >= $autoCargoStoredMax)
                {
                    $autoCargoStoredMaxMatchIds = (($scoutCard['AutonomousCargoStored'] > $autoCargoStoredMax) ? array() : $autoCargoStoredMaxMatchIds);
                    $autoCargoStoredMax = $scoutCard['AutonomousCargoStored'];
                    $autoCargoStoredMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['TeleopHatchPanelsSecured'] >= $teleopHatchPanelsMax)
                {
                    $teleopHatchPanelsMaxMatchIds = (($scoutCard['TeleopHatchPanelsSecured'] > $teleopHatchPanelsMax) ? array() : $teleopHatchPanelsMaxMatchIds);
                    $teleopHatchPanelsMax = $scoutCard['TeleopHatchPanelsSecured'];
                    $teleopHatchPanelsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['TeleopCargoStored'] >= $teleopCargoStoredMax)
                {
                    $teleopCargoStoredMaxMatchIds = (($scoutCard['TeleopCargoStored'] > $teleopCargoStoredMax) ? array() : $teleopCargoStoredMaxMatchIds);
                    $teleopCargoStoredMax = $scoutCard['TeleopCargoStored'];
                    $teleopCargoStoredMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['AutonomousHatchPanelsSecuredAttempts'] >= $autoHatchPanelsAttemptsMax)
                {
                    $autoHatchPanelsAttemptsMaxMatchIds = (($scoutCard['AutonomousHatchPanelsSecuredAttempts'] > $autoHatchPanelsAttemptsMax) ? array() : $autoHatchPanelsAttemptsMaxMatchIds);
                    $autoHatchPanelsAttemptsMax = $scoutCard['AutonomousHatchPanelsSecuredAttempts'];
                    $autoHatchPanelsAttemptsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['AutonomousCargoStoredAttempts'] >= $autoCargoStoredAttemptsMax)
                {
                    $autoCargoStoredAttemptsMaxMatchIds = (($scoutCard['AutonomousCargoStoredAttempts'] > $autoCargoStoredAttemptsMax) ? array() : $autoCargoStoredAttemptsMaxMatchIds);
                    $autoCargoStoredAttemptsMax = $scoutCard['AutonomousCargoStoredAttempts'];
                    $autoCargoStoredAttemptsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['TeleopHatchPanelsSecuredAttempts'] >= $teleopHatchPanelsAttemptsMax)
                {
                    $teleopHatchPanelsAttemptsMaxMatchIds = (($scoutCard['TeleopHatchPanelsSecuredAttempts'] > $teleopHatchPanelsAttemptsMax) ? array() : $teleopHatchPanelsAttemptsMaxMatchIds);
                    $teleopHatchPanelsAttemptsMax = $scoutCard['TeleopHatchPanelsSecuredAttempts'];
                    $teleopHatchPanelsAttemptsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['TeleopCargoStoredAttempts'] >= $teleopCargoStoredAttemptsMax)
                {
                    $teleopCargoStoredAttemptsMaxMatchIds = (($scoutCard['TeleopCargoStoredAttempts'] > $teleopCargoStoredAttemptsMax) ? array() : $teleopCargoStoredAttemptsMaxMatchIds);
                    $teleopCargoStoredAttemptsMax = $scoutCard['TeleopCargoStoredAttempts'];
                    $teleopCargoStoredAttemptsMaxMatchIds[] = $match->toString();
                }

                if(empty($scoutCard['EndGameReturnedToHabitat'])) {
                    if (0 >= $endGameReturnedToHabitatMax) {
                        $endGameReturnedToHabitatMaxMatchIds = (($scoutCard['EndGameReturnedToHabitat'] > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                        $endGameReturnedToHabitatMax = 0;
                        $endGameReturnedToHabitatMaxMatchIds[] = $match->toString();
                    }
                }
                else if($scoutCard['EndGameReturnedToHabitat'] >= $endGameReturnedToHabitatMax)
                {
                    $endGameReturnedToHabitatMaxMatchIds = (($scoutCard['EndGameReturnedToHabitat'] > $endGameReturnedToHabitatMax) ? array() : $endGameReturnedToHabitatMaxMatchIds);
                    $endGameReturnedToHabitatMax = $scoutCard['EndGameReturnedToHabitat'];
                    $endGameReturnedToHabitatMaxMatchIds[] = $match->toString();
                }

                if(empty($scoutCard['EndGameReturnedToHabitatAttempts'])) {
                    if (0 >= $endGameReturnedToHabitatAttemptsMax) {
                        $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                        $endGameReturnedToHabitatAttemptsMax = 0;
                        $endGameReturnedToHabitatAttemptsMaxMatchIds[] = $match->toString();
                    }
                }
                else if($scoutCard['EndGameReturnedToHabitatAttempts'] >= $endGameReturnedToHabitatAttemptsMax) {
                    $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                    $endGameReturnedToHabitatAttemptsMax = $scoutCard['EndGameReturnedToHabitatAttempts'];
                    $endGameReturnedToHabitatAttemptsMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['DefenseRating'] >= $postGameDefenseRatingMax && $scoutCard['DefenseRating'] != 0)
                {
                    $postGameDefenseRatingMaxMatchIds = (($scoutCard['DefenseRating'] < $postGameDefenseRatingMax) ? array() : $postGameDefenseRatingMaxMatchIds);
                    $postGameDefenseRatingMax = $scoutCard['DefenseRating'];
                    $postGameDefenseRatingMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['OffenseRating'] >= $postGameOffenseRatingMax && $scoutCard['OffenseRating'] != 0)
                {
                    $postGameOffenseRatingMaxMatchIds = (($scoutCard['OffenseRating'] < $postGameOffenseRatingMax) ? array() : $postGameOffenseRatingMaxMatchIds);
                    $postGameOffenseRatingMax = $scoutCard['OffenseRating'];
                    $postGameOffenseRatingMaxMatchIds[] = $match->toString();
                }

                if($scoutCard['DriveRating'] >= $postGameDriveRatingMax)
                {
                    $postGameDriveRatingMaxMatchIds = (($scoutCard['DriveRating'] < $postGameDriveRatingMax) ? array() : $postGameDriveRatingMaxMatchIds);
                    $postGameDriveRatingMax = $scoutCard['DriveRating'];
                    $postGameDriveRatingMaxMatchIds[] = $match->toString();
                }

                $i++;

            }

            if($removeMin == 'false') {
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
                $data[] = ($scoutCardCount - $nulledDefenseRatings) == 0 ? 0 : round($postGameDefenseRating / ($scoutCardCount - $nulledDefenseRatings), 2);
                $data[] = ($scoutCardCount - $nulledOffenseRatings) == 0 ? 0 : round($postGameOffenseRating / ($scoutCardCount - $nulledOffenseRatings), 2);
                $data[] = round($postGameDriveRating / $scoutCardCount, 2);
                
                $return_array[] = $data;
            }

            if($removeMax == 'false') {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $teamNumber .'">' . $teamNumber . '</a>';;
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