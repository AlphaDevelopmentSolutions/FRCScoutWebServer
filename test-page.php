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
require_once(ROOT_DIR . "/classes/tables/PitCards.php");
require_once(ROOT_DIR . "/classes/tables/RobotInfoKeys.php");
//
//foreach(PitCards::getObjects() as $pitCard)
//{
//    foreach ($pitCard as $key => $value)
//    {
//        if ($key != 'Id' &&
//            $key != 'TeamId' &&
//            $key != 'EventId' &&
//            $key != 'CompletedBy')
//        {
//            $robotinfo = new RobotInfo();
//            $robotinfo->YearId = '2019';
//            $robotinfo->EventId = $pitCard->EventId;
//            $robotinfo->TeamId = $pitCard->TeamId;
//
//            if (strpos($key, 'Drive') !== false || strpos($key, 'Robot') !== false)
//                $robotinfo->PropertyState = RobotInfoKeyStates::PreGame;
//
//            else if (strpos($key, 'Auto') !== false)
//                $robotinfo->PropertyState = RobotInfoKeyStates::Autonomous;
//
//            else if (strpos($key, 'Teleop') !== false)
//                $robotinfo->PropertyState = RobotInfoKeyStates::Teleop;
//
//            else if (strpos($key, 'Return') !== false)
//                $robotinfo->PropertyState = RobotInfoKeyStates::EndGame;
//
//            else if (strpos($key, 'Notes') !== false)
//                $robotinfo->PropertyState = RobotInfoKeyStates::PostGame;
//
//
//
//            $robotinfo->PropertyName = $key;
//            $robotinfo->PropertyValue = $value;
//
////           echo  $robotinfo->save();
//        }
//
//    }
//}


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
