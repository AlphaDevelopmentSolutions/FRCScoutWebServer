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
            $postGameDefenseRating = 1;
            $postGameOffenseRating = 1;
            $postGameDriveRating = 1;

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
            $postGameDefenseRatingMax = 1;
            $postGameOffenseRatingMax = 1;
            $postGameDriveRatingMax = 1;

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

            foreach (ScoutCards::getScoutCardsForTeam($team['Id'], $eventId) as $scoutCard)
            {

                //calc min
                if($scoutCard['AutonomousExitHabitat'] == 1)
                {
                    if($scoutCard['PreGameStartingLevel']  <= $autoExitHabitatMin)
                    {
                        $autoExitHabitatMinMatchIds = (($scoutCard['PreGameStartingLevel'] < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                        $autoExitHabitatMin = $scoutCard['PreGameStartingLevel'];
                        $autoExitHabitatMinMatchIds[] = $scoutCard['MatchId'];
                    }
                }
                else if(0 <= $autoExitHabitatMin)
                {
                    $autoExitHabitatMinMatchIds = ((0 < $autoExitHabitatMin) ? array() : $autoExitHabitatMinMatchIds);
                    $autoExitHabitatMin = $scoutCard['PreGameStartingLevel'];
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

                if($scoutCard['AutonomousHatchPanelsSecuredAttempts'] <= $autoHatchPanelsAttemptsMin)
                {
                    $autoHatchPanelsAttemptsMinMatchIds = (($scoutCard['AutonomousHatchPanelsSecuredAttempts'] < $autoHatchPanelsAttemptsMin) ? array() : $autoHatchPanelsAttemptsMinMatchIds);
                    $autoHatchPanelsAttemptsMin = $scoutCard['AutonomousHatchPanelsSecuredAttempts'];
                    $autoHatchPanelsAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['AutonomousCargoStoredAttempts'] <= $autoCargoStoredAttemptsMin)
                {
                    $autoCargoStoredAttemptsMinMatchIds = (($scoutCard['AutonomousCargoStoredAttempts'] < $autoCargoStoredAttemptsMin) ? array() : $autoCargoStoredAttemptsMinMatchIds);
                    $autoCargoStoredAttemptsMin = $scoutCard['AutonomousCargoStoredAttempts'];
                    $autoCargoStoredAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopHatchPanelsSecuredAttempts'] <= $teleopHatchPanelsAttemptsMin)
                {
                    $teleopHatchPanelsAttemptsMinMatchIds = (($scoutCard['TeleopHatchPanelsSecuredAttempts'] < $teleopHatchPanelsAttemptsMin) ? array() : $teleopHatchPanelsAttemptsMinMatchIds);
                    $teleopHatchPanelsAttemptsMin = $scoutCard['TeleopHatchPanelsSecuredAttempts'];
                    $teleopHatchPanelsAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopCargoStoredAttempts'] <= $teleopCargoStoredAttemptsMin)
                {
                    $teleopCargoStoredAttemptsMinMatchIds = (($scoutCard['TeleopCargoStoredAttempts'] < $teleopCargoStoredAttemptsMin) ? array() : $teleopCargoStoredAttemptsMinMatchIds);
                    $teleopCargoStoredAttemptsMin = $scoutCard['TeleopCargoStoredAttempts'];
                    $teleopCargoStoredAttemptsMinMatchIds[] = $scoutCard['MatchId'];
                }

                if(empty($scoutCard['EndGameReturnedToHabitat']))
                {
                    if (0 <= $endGameReturnedToHabitatMin) {
                        $endGameReturnedToHabitatMinMatchIds = (($scoutCard['EndGameReturnedToHabitat'] < $endGameReturnedToHabitatMin) ? array() : $endGameReturnedToHabitatMinMatchIds);
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

                if(empty($scoutCard['EndGameReturnedToHabitatAttempts'])) {
                    if (0 <= $endGameReturnedToHabitatAttemptsMin) {
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

                if($scoutCard['DefenseRating'] <= $postGameDefenseRatingMin && $scoutCard['DefenseRating'] != 0)
                {
                    $postGameDefenseRatingMinMatchIds = (($scoutCard['DefenseRating'] < $postGameDefenseRatingMin) ? array() : $postGameDefenseRatingMinMatchIds);
                    $postGameDefenseRatingMin = $scoutCard['DefenseRating'];
                    $postGameDefenseRatingMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['OffenseRating'] <= $postGameOffenseRatingMin && $scoutCard['OffenseRating'] != 0)
                {
                    $postGameOffenseRatingMinMatchIds = (($scoutCard['OffenseRating'] < $postGameOffenseRatingMin) ? array() : $postGameOffenseRatingMinMatchIds);
                    $postGameOffenseRatingMin = $scoutCard['OffenseRating'];
                    $postGameOffenseRatingMinMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['DriveRating'] <= $postGameDriveRatingMin && $scoutCard['DriveRating'] != 0)
                {
                    $postGameDriveRatingMinMatchIds = (($scoutCard['DriveRating'] < $postGameDriveRatingMin) ? array() : $postGameDriveRatingMinMatchIds);
                    $postGameDriveRatingMin = $scoutCard['DriveRating'];
                    $postGameDriveRatingMinMatchIds[] = $scoutCard['MatchId'];
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
                        $autoExitHabitatMaxMatchIds[] = $scoutCard['MatchId'];
                    }
                }
                else if(0 >= $autoExitHabitatMax)
                {
                    $autoExitHabitatMaxMatchIds = ((0 > $autoExitHabitatMax) ? array() : $autoExitHabitatMaxMatchIds);
                    $autoExitHabitatMax = $scoutCard['PreGameStartingLevel'];
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

                if($scoutCard['AutonomousHatchPanelsSecuredAttempts'] >= $autoHatchPanelsAttemptsMax)
                {
                    $autoHatchPanelsAttemptsMaxMatchIds = (($scoutCard['AutonomousHatchPanelsSecuredAttempts'] > $autoHatchPanelsAttemptsMax) ? array() : $autoHatchPanelsAttemptsMaxMatchIds);
                    $autoHatchPanelsAttemptsMax = $scoutCard['AutonomousHatchPanelsSecuredAttempts'];
                    $autoHatchPanelsAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['AutonomousCargoStoredAttempts'] >= $autoCargoStoredAttemptsMax)
                {
                    $autoCargoStoredAttemptsMaxMatchIds = (($scoutCard['AutonomousCargoStoredAttempts'] > $autoCargoStoredAttemptsMax) ? array() : $autoCargoStoredAttemptsMaxMatchIds);
                    $autoCargoStoredAttemptsMax = $scoutCard['AutonomousCargoStoredAttempts'];
                    $autoCargoStoredAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopHatchPanelsSecuredAttempts'] >= $teleopHatchPanelsAttemptsMax)
                {
                    $teleopHatchPanelsAttemptsMaxMatchIds = (($scoutCard['TeleopHatchPanelsSecuredAttempts'] > $teleopHatchPanelsAttemptsMax) ? array() : $teleopHatchPanelsAttemptsMaxMatchIds);
                    $teleopHatchPanelsAttemptsMax = $scoutCard['TeleopHatchPanelsSecuredAttempts'];
                    $teleopHatchPanelsAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['TeleopCargoStoredAttempts'] >= $teleopCargoStoredAttemptsMax)
                {
                    $teleopCargoStoredAttemptsMaxMatchIds = (($scoutCard['TeleopCargoStoredAttempts'] > $teleopCargoStoredAttemptsMax) ? array() : $teleopCargoStoredAttemptsMaxMatchIds);
                    $teleopCargoStoredAttemptsMax = $scoutCard['TeleopCargoStoredAttempts'];
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

                if(empty($scoutCard['EndGameReturnedToHabitatAttempts'])) {
                    if (0 >= $endGameReturnedToHabitatAttemptsMax) {
                        $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                        $endGameReturnedToHabitatAttemptsMax = 0;
                        $endGameReturnedToHabitatAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                    }
                }
                else if($scoutCard['EndGameReturnedToHabitatAttempts'] >= $endGameReturnedToHabitatAttemptsMax) {
                    $endGameReturnedToHabitatAttemptsMaxMatchIds = (($scoutCard['EndGameReturnedToHabitatAttempts'] > $endGameReturnedToHabitatAttemptsMax) ? array() : $endGameReturnedToHabitatAttemptsMaxMatchIds);
                    $endGameReturnedToHabitatAttemptsMax = $scoutCard['EndGameReturnedToHabitatAttempts'];
                    $endGameReturnedToHabitatAttemptsMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['DefenseRating'] >= $postGameDefenseRatingMax && $scoutCard['DefenseRating'] != 0)
                {
                    $postGameDefenseRatingMaxMatchIds = (($scoutCard['DefenseRating'] < $postGameDefenseRatingMax) ? array() : $postGameDefenseRatingMaxMatchIds);
                    $postGameDefenseRatingMax = $scoutCard['DefenseRating'];
                    $postGameDefenseRatingMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['OffenseRating'] >= $postGameOffenseRatingMax && $scoutCard['OffenseRating'] != 0)
                {
                    $postGameOffenseRatingMaxMatchIds = (($scoutCard['OffenseRating'] < $postGameOffenseRatingMax) ? array() : $postGameOffenseRatingMaxMatchIds);
                    $postGameOffenseRatingMax = $scoutCard['OffenseRating'];
                    $postGameOffenseRatingMaxMatchIds[] = $scoutCard['MatchId'];
                }

                if($scoutCard['DriveRating'] >= $postGameDriveRatingMax && $scoutCard['DriveRating'] != 0)
                {
                    $postGameDriveRatingMaxMatchIds = (($scoutCard['DriveRating'] < $postGameDriveRatingMax) ? array() : $postGameDriveRatingMaxMatchIds);
                    $postGameDriveRatingMax = $scoutCard['DriveRating'];
                    $postGameDriveRatingMaxMatchIds[] = $scoutCard['MatchId'];
                }

                $i++;

            }

            if($removeMin == 'false') {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $teamNumber .'">' . $teamNumber . '</a>';
                $data[] = 'MIN';
                $data[] = $autoExitHabitatMin;
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
                $data[] = round($postGameDefenseRating / $scoutCardCount, 2);
                $data[] = round($postGameOffenseRating / $scoutCardCount, 2);
                $data[] = round($postGameDriveRating / $scoutCardCount, 2);
                
                $return_array[] = $data;
            }

            if($removeMax == 'false') {
                $data = array();
                $data[] = '<a target="_blank" href="/team-matches.php?eventId=' . $eventId . '&teamId=' . $teamNumber .'">' . $teamNumber . '</a>';;
                $data[] = 'MAX';
                $data[] = $autoExitHabitatMax;
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