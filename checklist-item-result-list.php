<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/Teams.php");
require_once(ROOT_DIR . "/classes/Events.php");
require_once(ROOT_DIR . "/classes/Matches.php");
require_once(ROOT_DIR . "/classes/ChecklistItems.php");

$eventId = $_GET['eventId'];
$matchId = $_GET['matchId'];

$event = Events::withId($eventId);

if(!empty($matchId))
    $match = Matches::withId($matchId);
?>

<!doctype html>
<html lang="en">
<head>
    <?php require_once('includes/meta.php') ?>
    <title>Checklist Item Results<?php echo ((!empty($match)) ? ' - ' . $match->toString() : '') ?></title>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Checklist Items', '/checklist-item-list.php?eventId=' . $event->BlueAllianceId);
    $navBarLinksArray[] = new NavBarLink('Completed Checklist Items', '/checklist-item-result-list.php?eventId=' . $event->BlueAllianceId, ((empty($match)) ? true : false));

    if(!empty($match))
        $navBarLinksArray[] = new NavBarLink('Completed Checklist Items - ' . $match->toString(), '', true);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event);

    echo $header->toHtml();
    ?>
    <main class="mdl-layout__content">

        <?php

        //no match selected, show match list
        if(empty($match))
        {
            foreach ($event->getMatches(null, Teams::withId(TEAM_NUMBER)) as $match)
                echo $match->toHtml('checklist-item-result-list.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key, 'View Completed Checklist Items', TEAM_NUMBER);
        }

        //match selected, show checklist item results for specified match
        else
        {
            foreach(ChecklistItems::getObjects() as $checklistItem)
            {
                foreach($checklistItem->getResults($match) as $checklistItemResult)
                    echo $checklistItemResult->toHtml();

            }
        }



        ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>
