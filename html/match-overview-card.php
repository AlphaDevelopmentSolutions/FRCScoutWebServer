<?php
require_once("config.php");
require_once("classes/ScoutCards.php");
require_once("classes/Events.php");
require_once("classes/Teams.php");
require_once("classes/Matches.php");

$eventId = $_GET['eventId'];
$matchId = $_GET['matchId'];
$allianceColor = $_GET['allianceColor'];

$event = new Events();
$event->load($eventId);

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Match <?php echo $matchId ?> Overview</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.deep_purple-pink.min.css">
    <link rel="stylesheet" href="css/styles.css">
      
     
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    </style>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            <?php include_once('includes/login-form.php') ?>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
          <h3>Match <?php echo $matchId ?> Overview</h3>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
          <a href="/" class="mdl-layout__tab">Events</a>
          <a href="/teams.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab ">Teams</a>
        </div>
          <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
              <a href="/stats.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab ">Stats</a>
              <a href="/match-overview.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab">Match Overview</a>
              <a href="/match-overview-card.php?eventId=<?php echo $event->BlueAllianceId; ?>&matchId=<?php echo $matchId ?>&allianceColor=BLUE" class="mdl-layout__tab <?php if($allianceColor == 'BLUE') echo 'is-active'?>">Match <?php echo $matchId; ?> Overview - BLUE ALLIANCE</a>
              <a href="/match-overview-card.php?eventId=<?php echo $event->BlueAllianceId; ?>&matchId=<?php echo $matchId ?>&allianceColor=RED" class="mdl-layout__tab <?php if($allianceColor == 'RED') echo 'is-active'?>">Match <?php echo $matchId; ?> Overview - RED ALLIANCE</a>
          </div>
      </header>
      <main class="mdl-layout__content">

          <div class="mdl-layout__tab-panel is-active" id="overview">
              <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                  <div class="mdl-card mdl-cell mdl-cell--12-col">

                      <?php

                      $scoutCardIds = array();

                      if($allianceColor == 'BLUE')
                          $scoutCardIds = Matches::getBlueAllianceScoutCardIds($eventId, $matchId);

                      else
                          $scoutCardIds = Matches::getRedAllianceScoutCardIds($eventId, $matchId);


                      foreach($scoutCardIds AS $scoutCardId)
                      {
                          $scoutCard = new ScoutCards();
                          $scoutCard->load($scoutCardId['Id']);

                          $team = new Teams();
                          $team->load($scoutCard->TeamId);

                          ?>



                      <h4 style="padding-left: 40px; padding-top: 10px;"><?php echo $team->Id; ?></h4>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->CompletedBy ?>" >
                              <label class="mdl-textfield__label" >Scouter</label>
                          </div>
                      </div>

                      <strong style="padding-left: 40px; padding-top: 10px;">Autonomous</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo (($scoutCard->AutonomousExitHabitat == 1) ? 'Yes' : 'No') ?>" >
                              <label class="mdl-textfield__label" >Exit Habitat</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousHatchPanelsSecured ?>" >
                              <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousCargoStored ?>" >
                              <label class="mdl-textfield__label" >Cargo Stored</label>
                          </div>
                      </div>

                      <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopHatchPanelsSecured ?>" >
                              <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopCargoStored ?>" >
                              <label class="mdl-textfield__label" >Cargo Stored</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopRocketsCompleted ?>" >
                              <label class="mdl-textfield__label" >Rockets Completed</label>
                          </div>
                      </div>

                      <strong style="padding-left: 40px; padding-top: 10px;">End Game</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->EndGameReturnedToHabitat ?>" >
                              <label class="mdl-textfield__label" >Returned To Habitat</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->Notes ?>" >
                              <label class="mdl-textfield__label" >Notes</label>
                          </div>
                      </div>

                      <?php } ?>
                  </div>
              </section>
          </div>

          
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  </body>
</html>
