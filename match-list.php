<?php

require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");

$eventId = $_GET['eventId'];

$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>
    <?php require_once('includes/meta.php') ?>
    <title>Matches</title>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Matches', '/match-list.php?eventId=' . $event->BlueAllianceId, true);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event, null, 'admin.php?yearId=' . $event->YearId);

    echo $header->toHtml();
    ?>
    <main class="mdl-layout__content">

        <?php

        foreach ($event->getMatches() as $match)
            echo $match->toHtml('/match-stats.php?eventId=' . $match->EventId . '&matchId=' . $match->Key, 'View Match Overview');

        ?>

        <?php require_once('includes/footer.php') ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>
