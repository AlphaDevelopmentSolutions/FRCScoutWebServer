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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title><?php echo $match->toString()?> Overview</title>

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
          <h3><?php echo $match->toString()?> Overview</h3>
        </div>

          <div class="version">Version <?php echo VERSION ?></div>
          <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
              <a href="/" class="mdl-layout__tab">Events</a>
              <a href="/match-overview.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab is-active">Matches</a>
              <a href="/teams.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab">Teams</a>
          </div>
          <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
              <a href="/match-overview-card.php?eventId=<?php echo $event->BlueAllianceId; ?>&matchId=<?php echo $matchId ?>&allianceColor=BLUE" class="mdl-layout__tab <?php if($allianceColor == 'BLUE') echo 'is-active'?>">BLUE ALLIANCE</a>
              <a href="/match-overview-card.php?eventId=<?php echo $event->BlueAllianceId; ?>&matchId=<?php echo $matchId ?>&allianceColor=RED" class="mdl-layout__tab <?php if($allianceColor == 'RED') echo 'is-active'?>">RED ALLIANCE</a>
          </div>
      </header>
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  </body>
</html>
