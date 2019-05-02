<?php
require_once("config.php");
require_once("classes/Teams.php");
require_once("classes/Events.php");
require_once("classes/Matches.php");
require_once("classes/ChecklistItems.php");
require_once("classes/ChecklistItemResults.php");

$eventId = $_GET['eventId'];
$matchId = $_GET['matchId'];

$event = Events::withId($eventId);

if(!empty($matchId))
    $match = Matches::withKey($matchId);
?>

<!doctype html>
<html lang="en">
<head>
    <?php require_once('includes/meta.php') ?>
    <title>Match Overview</title>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Checklist Items', '/checklist-item-list.php?eventId=' . $event->BlueAllianceId, false);
    $navBarLinksArray[] = new NavBarLink('Completed Checklist Items', '/checklist-item-result-list.php?eventId=' . $event->BlueAllianceId, ((empty($match)) ? true : false));

    if(!empty($match))
        $navBarLinksArray[] = new NavBarLink('Completed Checklist Items - ' . $match->toString(), '', true);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event->BlueAllianceId);

    echo $header->toString();
    ?>
    <main class="mdl-layout__content">

        <?php

        //no match selected, show match list
        if(empty($match))
        {
            foreach (Matches::getMatches($event, Teams::withId(TEAM_NUMBER)) as $match)
            {
                $match = Matches::withProperties($match);

                echo $match->toHtml('checklist-item-result-list.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key, 'View Checklist Items', TEAM_NUMBER);
            }
        }

        //match selected, show checklist item results for specified match
        else
        {
            foreach(ChecklistItemResults::getChecklistItemResults($match) as $checklistItemResult)
            {
                $checklistItemResult = ChecklistItemResults::withProperties($checklistItemResult);

                echo $checklistItemResult->toHtml();
            }
        }



        ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>
