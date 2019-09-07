<?php
require_once("config.php");
require_once("classes/Events.php");
require_once("classes/ChecklistItems.php");

$eventId = $_GET['eventId'];

$event = Events::withId($eventId);
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
    $navBarLinksArray[] = new NavBarLink('Checklist Items', '', true);
    $navBarLinksArray[] = new NavBarLink('Completed Checklist Items', '/checklist-item-result-list.php?eventId=' . $event->BlueAllianceId, false);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event->BlueAllianceId);

    echo $header->toString();
    ?>
    <main class="mdl-layout__content">

        <?php

        foreach(ChecklistItems::getChecklistItems() as $checklistItem)
        {
            $checklistItem = ChecklistItems::withProperties($checklistItem);

            ?>
            <div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                        <div class="mdl-card__supporting-text">
                            <h4><?php echo $checklistItem->Title ?></h4>
                            <?php echo $checklistItem->Description ?><br><br>
                        </div>
                    </div>
                </section>
            </div>
            <?php
        }

        ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>
