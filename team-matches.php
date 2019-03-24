<?php
require_once("config.php");
require_once("classes/Teams.php");
require_once("classes/ScoutCards.php");
require_once("classes/PitCards.php");
require_once("classes/Events.php");

$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];

$team = new Teams();
$team->load($teamId);

$event = new Events();
$event->load($eventId);

$pitCard = new PitCards();
$pitCard->load(PitCards::getNewestPitCard($team->Id, $event->BlueAllianceId)['0']['Id']);

$url = "http://scouting.wiredcats5885.ca/ajax/GetOPRStats.php";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    "eventCode=" . $event->BlueAllianceId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
$stats = json_decode($response, true);

$opr = $stats['oprs']['frc' . $pitCard->TeamId];
$dpr = $stats['dprs']['frc' . $pitCard->TeamId];
$ccwms = $stats['ccwms']['frc' . $pitCard->TeamId];

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title><?php echo $team->Id . ' - ' . $team->Name ?></title>

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
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>




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
          <h3><?php echo $team->Id . ' - ' . $team->Name ?></h3><br>
        </div>
          <div class="mdl-layout--large-screen-only mdl-layout__header-row">
              <h3><?php echo $team->City . ', ' . $team->StateProvince . ', ' . $team->Country ?></h3><br>
          </div>
          <div class="mdl-layout--large-screen-only mdl-layout__header-row">
              <?php
              if(!empty($team->FacebookURL))
              {
                  echo
                  '
                    <a target="_blank" href="https://www.facebook.com/' . $team->FacebookURL . '">
                        <i class="fab fa-facebook-f header-icon"></i>
                    </a>
                  ';
              }

              if(!empty($team->TwitterURL))
              {
                  echo
                      '
                    <a target="_blank" href="https://www.twitter.com/' . $team->TwitterURL . '">
                        <i class="fab fa-twitter header-icon"></i>
                    </a>
                  ';
              }

              if(!empty($team->InstagramURL))
              {
                  echo
                      '
                    <a target="_blank" href="https://www.instagram.com/' . $team->InstagramURL . '">
                        <i class="fab fa-instagram header-icon"></i>
                    </a>
                  ';
              }

              if(!empty($team->YoutubeURL))
              {
                  echo
                      '
                    <a target="_blank" href="https://www.youtube.com/' . $team->YoutubeURL . '">
                        <i class="fab fa-youtube header-icon"></i>
                    </a>
                  ';
              }

              if(!empty($team->WebsiteURL))
              {
                  echo
                      '
                    <a target="_blank" href="' . $team->WebsiteURL . '">
                        <i class="fas fa-globe header-icon"></i>
                    </a>
                  ';
              }

              ?>

          </div>
          <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
              <h6 style="margin: unset"><strong>OPR:</strong> <?php echo $opr ?></h6>
          </div>

          <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
              <h6 style="margin: unset"><strong>DPR:</strong> <?php echo $dpr ?></h6>
          </div>

          <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
              <h6 style="margin: unset"><strong>CCWMS:</strong> <?php echo $ccwms ?></h6>
          </div>
          <div id="quick-stats" style="padding-left: 40px" hidden>
              <h6 style="margin: unset"><strong>Drivetrain:</strong> <?php echo $pitCard->DriveStyle ?></h6>
              <h6 style="margin: unset"><strong>Robot Weight:</strong> <?php echo $pitCard->RobotWeight ?></h6>
              <h6 style="margin: unset"><strong>Robot Length:</strong> <?php echo $pitCard->RobotLength ?></h6>
              <h6 style="margin: unset"><strong>Robot Width:</strong> <?php echo $pitCard->RobotWidth ?></h6>
              <h6 style="margin: unset"><strong>Robot Height:</strong> <?php echo $pitCard->RobotHeight ?></h6>

              <h6 style="margin: unset"><strong>Auto Exit Habitat:</strong> <?php echo $pitCard->AutoExitHabitat ?></h6>
              <h6 style="margin: unset"><strong>Auto Hatch Panels:</strong> <?php echo $pitCard->AutoHatch ?></h6>
              <h6 style="margin: unset"><strong>Auto Cargo:</strong> <?php echo $pitCard->AutoCargo ?></h6>

              <h6 style="margin: unset"><strong>Teleop Hatch:</strong> <?php echo $pitCard->TeleopHatch ?></h6>
              <h6 style="margin: unset"><strong>Teleop Cargo:</strong> <?php echo $pitCard->TeleopCargo ?></h6>

              <h6 style="margin: unset"><strong>Return To Habitat:</strong> <?php echo $pitCard->ReturnToHabitat ?></h6>

              <h6 style="margin: unset"><strong>Notes:</strong> <?php echo $pitCard->Notes ?></h6>
              <h6 style="margin: unset"><strong>Completed By:</strong> <?php echo $pitCard->CompletedBy ?></h6>
          </div>
          <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
              <h6 style="margin: unset" ><a id="show-stats-btn" href="#" style="color:white" onclick="showQuickStats()">Show More</a></h6>
          </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
          <div class="version">Version <?php echo VERSION ?></div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
          <a href="/" class="mdl-layout__tab">Events</a>
          <a href="/teams.php?eventId=<?php echo $eventId; ?>" class="mdl-layout__tab ">Teams</a>
          <a href="" class="mdl-layout__tab is-active">Team <?php echo $teamId; ?></a>
        </div>
          <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
              <a href="/team-matches.php?eventId=<?php echo $event->BlueAllianceId; ?>&teamId=<?php echo $team->Id; ?>" class="mdl-layout__tab is-active">Matches</a>
              <a href="/team-pits.php?eventId=<?php echo $event->BlueAllianceId; ?>&teamId=<?php echo $team->Id; ?>" class="mdl-layout__tab">Pits</a>
          </div>
      </header>
      <main class="mdl-layout__content">

          <?php if(loggedIn())
              {
                  //temp disabled due to new table design
//                  echo
//                  '<button onclick="window.location = \'/scout-card.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id .'\'" style="position: fixed; bottom: 0 !important; margin-bottom: 1em;" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored mdl-shadow--4dp mdl-color--accent" id="add" data-upgraded=",MaterialButton,MaterialRipple">
//                      <i class="material-icons" role="presentation">add</i>
//                      <span class="visuallyhidden">Add</span>
//                      <span class="mdl-button__ripple-container">
//                            <span class="mdl-ripple is-animating" style="width: 160.392px; height: 160.392px; transform: translate(-50%, -50%) translate(37px, 28px);"></span>
//                        </span>
//                  </button>';
              }

          ?>

          <?php

          foreach(ScoutCards::getScoutCardsForTeam($teamId, $eventId) as $scoutCard)
          {
            echo
            '
                <div class="mdl-layout__tab-panel is-active" id="overview">
                  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                      <div class="mdl-card__supporting-text">
                        <h4>Match ' . $scoutCard['MatchId'] . '</h4>
                        Blue Alliance Score - ' . $scoutCard['BlueAllianceFinalScore'] . '<br><br>
                        Red Alliance Score - ' . $scoutCard['RedAllianceFinalScore'] .
                      '</div>
                      <div class="mdl-card__actions">
                        <a href="/scout-card.php?scoutCardId=' . $scoutCard['Id']  .'" class="mdl-button">View</a>
                      </div>
                    </div>
                  </section>
                </div>
            ';
          }

          ?>

          
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
    <script>
        function showQuickStats()
        {

            if($('#quick-stats').attr('hidden'))
            {
                $('#show-stats-btn').html('Show Less');
                $('#quick-stats').removeAttr('hidden');
            }

            else
            {
                $('#show-stats-btn').html('Show More');
                $('#quick-stats').attr('hidden', 'hidden');
            }

        }
    </script>
  </body>
</html>
