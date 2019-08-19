<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItems.php");

$eventId = $_GET['eventId'];

$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>
    <?php require_once('includes/meta.php') ?>
    <title>Checklist Items</title>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Checklist Items', '', true);
    $navBarLinksArray[] = new NavBarLink('Completed Checklist Items', '/checklist-item-result-list.php?eventId=' . $event->BlueAllianceId);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event, null, 'admin-year.php?yearId=' . $event->YearId);

    echo $header->toHtml();
    ?>
    <main class="mdl-layout__content">

        <?php

        foreach(ChecklistItems::getObjects() as $checklistItem)
        {
           echo $checklistItem->toHtml();
        }

        ?>

        <?php require_once('includes/footer.php') ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>
