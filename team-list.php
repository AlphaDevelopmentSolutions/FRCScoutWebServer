<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/Events.php");

$eventId = $_GET['eventId'];

$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo $event->Name; ?></title>
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Teams', '/team-list.php?eventId=' . $event->BlueAllianceId, true);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event->BlueAllianceId);

    echo $header->toHtml();
    ?>
    <main class="mdl-layout__content">

        <?php

        foreach ($event->getTeams() as $team)
            echo $team->toHtml($event);

        ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>
