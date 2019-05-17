<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/Teams.php");
require_once(ROOT_DIR . "/classes/tables/Events.php");
require_once(ROOT_DIR . "/classes/tables/ScoutCardInfo.php");
require_once(ROOT_DIR . "/classes/tables/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/ScoutCardInfoArray.php");
require_once(ROOT_DIR . "/classes/tables/Matches.php");


$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];

$team = Teams::withId($teamId);
$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

    <input id="eventId" hidden disabled value="<?php echo $event->BlueAllianceId ?>">
    <input id="teamId" hidden disabled value="<?php echo $team->Id ?>">

    <main class="mdl-layout__content">

        <?php

        $array = ScoutCardInfo::forTeam(null, $event,null, $team);

        echo $array->toHtml();
        ?>

        hello
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>
