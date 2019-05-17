<?php
/**
 * THIS PAGE IS TEMPORARY FOR THE SOON SCOUT CARD SYSTEM REWORK
 */

require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/ScoutCards.php");
require_once(ROOT_DIR . "/classes/tables/Events.php");
require_once(ROOT_DIR . "/classes/tables/Matches.php");
require_once(ROOT_DIR . "/classes/tables/Teams.php");
require_once(ROOT_DIR . "/classes/tables/Years.php");
require_once(ROOT_DIR . "/classes/tables/RobotInfo.php");
require_once(ROOT_DIR . "/classes/tables/ScoutCardInfo.php");
require_once(ROOT_DIR . "/classes/tables/RobotInfoKeys.php");
//
//foreach(ScoutCards::getObjects() as $scoutCard)
//{
//    foreach ($scoutCard as $key => $value)
//    {
//        if ($key != 'Id' &&
//            $key != 'TeamId' &&
//            $key != 'MatchId' &&
//            $key != 'EventId' &&
//            $key != 'CompletedBy' &&
//            $key != 'RedAllianceFinalScore' &&
//            $key != 'BlueAllianceFinalScore' &&
//            $key != 'AllianceColor' &&
//            $key != 'CompletedDate')
//        {
//            $scoutCardInfo = new ScoutCardInfo();
//            $scoutCardInfo->YearId = '2019';
//            $scoutCardInfo->EventId = $scoutCard->EventId;
//            $scoutCardInfo->TeamId = $scoutCard->TeamId;
//            $scoutCardInfo->MatchId = $scoutCard->MatchId;
//            $scoutCardInfo->CompletedBy = $scoutCard->CompletedBy;
//
//            if (strpos($key, 'PreGame') !== false)
//                $scoutCardInfo->PropertyState = "Pre Game";
//
//            else if (strpos($key, 'Autonomous') !== false)
//                $scoutCardInfo->PropertyState = 'Autonomous';
//
//            else if (strpos($key, 'Teleop') !== false)
//                $scoutCardInfo->PropertyState = 'Teleop';
//
//            else if (strpos($key, 'EndGame') !== false)
//                $scoutCardInfo->PropertyState = 'End Game';
//
//            else if (strpos($key, 'Defense') !== false
//                || strpos($key, 'Offense') !== false
//                || strpos($key, 'Drive') !== false
//                || strpos($key, 'Notes') !== false
//            )
//                $scoutCardInfo->PropertyState = 'Post Game';
//
//
//
//            $scoutCardInfo->PropertyKey = $key;
//            $scoutCardInfo->PropertyValue = $value;
//
//           echo  serialize($scoutCardInfo->save());
//
////            echo serialize($scoutCardInfo) . '<br><br>';
//        }
//
//    }
//}
//
//$database = new Database();
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Starting Position" WHERE propertykey = "pregamestartingposition"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Starting Level" WHERE propertykey = "pregamestartinglevel"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Starting Piece" WHERE propertykey = "pregamestartingpiece"', array(), array());
//
//
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Exit HAB" WHERE propertykey = "autonomousexithabitat"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Hatches Picked Up" WHERE propertykey = "autonomoushatchpanelspickedup"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Hatches Dropped" WHERE propertykey = "autonomoushatchpanelssecuredattempts"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Hatches Secured" WHERE propertykey = "AutonomousHatchPanelsSecured"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Cargo Picked Up" WHERE propertykey = "autonomousCargopickedup"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Cargo Dropped" WHERE propertykey = "autonomousCargostoredattempts"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Cargo Stored" WHERE propertykey = "AutonomousCargostored"', array(), array());
//
//
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Hatches Picked Up" WHERE propertykey = "teleophatchpanelspickedup"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Hatches Dropped" WHERE propertykey = "teleophatchpanelssecuredattempts"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Hatches Secured" WHERE propertykey = "teleopHatchPanelsSecured"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Cargo Picked Up" WHERE propertykey = "teleopCargopickedup"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Cargo Dropped" WHERE propertykey = "teleopCargostoredattempts"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Cargo Stored" WHERE propertykey = "teleopCargostored"', array(), array());
//
//
//
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Returned To HAB" WHERE propertykey = "EndGameReturnedToHabitat"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Returned To HAB Failed Attempt" WHERE propertykey = "EndGameReturnedToHabitatAttempts"', array(), array());
//
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Defense Rating" WHERE propertykey = "defenseRating"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Offense Rating" WHERE propertykey = "offenserating"', array(), array());
//$results = $database->query('UPDATE scout_card_info SET propertykey = "Drive Rating" WHERE propertykey = "driverating"', array(), array());
//
//
//
//$database->close();


?>

<!doctype html>
<html lang="en">
<head>
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div id="demo-toast-example" class="mdl-js-snackbar mdl-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>
<dialog class="mdl-dialog">
    <h4 class="mdl-dialog__title">Are you sure?</h4>
    <div class="mdl-dialog__content">
        <p>
            Are you sure you want to delete this scout card?
        </p>
    </div>
    <div class="mdl-dialog__actions">
        <button type="button" class="mdl-button negative">No</button>
        <button type="button" class="mdl-button positive">Yes</button>
    </div>
</dialog>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

    <main class="mdl-layout__content">

        <?php
            $event = Events::withId('2019oncmp2');
            $team = Teams::withId('1285');

            $array = RobotInfo::forTeam(null, $event, $team);

            echo $array->toHtml();
        ?>

    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>

</body>
</html>
