<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/Events.php");
require_once(ROOT_DIR . "/classes/Matches.php");

$eventId = $_GET['eventId'];
$matchId = $_GET['matchId'];
$allianceColor = $_GET['allianceColor'];

$event = Events::withId($eventId);

$match = Matches::withId($matchId);

?>

<!doctype html>
<html lang="en">
  <head>

    <title><?php echo $match->toString()?> Overview</title>
    <?php require_once('includes/meta.php') ?>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <?php
        $navBarArray = new NavBarArray();

        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Matches', '/match-list.php?eventId=' . $event->BlueAllianceId);
        $navBarLinksArray[] = new NavBarLink($match->toString(), '', true);

        $navBarArray[] = new NavBar($navBarLinksArray);

        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Blue Alliance', '/match.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key . '&allianceColor=BLUE', ($allianceColor == 'BLUE'));
        $navBarLinksArray[] = new NavBarLink('Red Alliance', '/match.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key . '&allianceColor=RED', ($allianceColor == 'RED'));

        $navBarArray[] = new NavBar($navBarLinksArray);

        $header = new Header($event->Name, $additionContent, $navBarArray, $event);

        echo $header->toHtml();

        ?>
      <main class="mdl-layout__content">

          <?php

          $scoutCards = $match->getScoutCards();

          foreach($scoutCards AS $scoutCard)
          {
              if($allianceColor == $scoutCard->AllianceColor)
                echo $scoutCard->toHtml();
          }


          ?>
      </main>
    </div>
    <?php require_once('includes/bottom-scripts.php') ?>
  </body>
</html>
