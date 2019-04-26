<?php
require_once("config.php");
require_once("classes/ScoutCards.php");
require_once("classes/Events.php");
require_once("classes/Teams.php");
require_once("classes/Matches.php");


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
        $navBarLinksArray[] = new NavBarLink('Matches', '/match-overview.php?eventId=' . $event->BlueAllianceId, false);
        $navBarLinksArray[] = new NavBarLink($match->toString(), '', true);

        $navBarArray[] = new NavBar($navBarLinksArray);

        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Blue Alliance', '/match-overview-card.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Id . '&allianceColor=BLUE', ($allianceColor == 'BLUE'));
        $navBarLinksArray[] = new NavBarLink('Red Alliance', '/match-overview-card.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Id . '&allianceColor=RED', ($allianceColor == 'RED'));

        $navBarArray[] = new NavBar($navBarLinksArray);

        $header = new Header($event->Name, $additionContent, $navBarArray, $event->BlueAllianceId);

        echo $header->toString();

        ?>
      <main class="mdl-layout__content">

          <?php require_once('includes/match-overview-card.php') ?>

          
          <div class="mdl-layout__tab-panel" id="stats">
<style>
.demo-card-wide.mdl-card {
  width: 60%;
/*    height: 1000px;*/
    margin: auto;
}
.demo-card-wide > .mdl-card__title {
  color: #fff;
  height: 176px;
/*  background: url('../assets/demos/welcome_card.jpg') center / cover;*/
    background-color: red;
                  }
.demo-card-wide > .mdl-card__menu {
  color: #fff;
}
</style>
              
          <section class="section--footer mdl-grid">
          </section>
        </div>

      </main>
    </div>
    <?php require_once('includes/bottom-scripts.php') ?>
  </body>
</html>
