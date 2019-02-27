<?php
require_once("config.php");
require_once("classes/PitCards.php");
require_once("classes/Events.php");

$pitCardId = $_GET['pitCardId'];

$pitCard = new PitCards();
$pitCard->load($pitCardId);

$event = new Events();
$event->load($pitCard->EventId);

$url = "http://scouting.wiredcats5885.ca/ajax/GetOPRStats.php";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
//    "eventCode=" . $event->BlueAllianceId);
    "eventCode=2018onwin");
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
    <title>Pit Card <?php echo $pitCard->Id ?></title>

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
          <h3>Pit Card <?php echo $pitCard->Id ?></h3>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
          <a href="/" class="mdl-layout__tab">Events</a>
          <a href="/teams.php?eventId=<?php echo $pitCard->EventId; ?>" class="mdl-layout__tab ">Teams</a>
          <a href="/team-pits.php?teamId=<?php echo $pitCard->TeamId?>&eventId=<?php echo $pitCard->EventId;?>" class="mdl-layout__tab">Team <?php echo $pitCard->TeamId; ?></a>
          <a href="" class="mdl-layout__tab is-active">Pit Card <?php echo $pitCard->Id; ?></a>
        </div>
      </header>
      <main class="mdl-layout__content">

          <div class="mdl-layout__tab-panel is-active" id="overview">
              <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                  <div class="mdl-card mdl-cell mdl-cell--12-col">
                      <strong style="padding-left: 40px; padding-top: 10px;">Pre Game</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeamId ?>" >
                              <label class="mdl-textfield__label" >Team Id</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->CompletedBy ?>" >
                              <label class="mdl-textfield__label" >Scouter</label>
                          </div>
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->DriveStyle ?>" >
                              <label class="mdl-textfield__label" >Drive Style</label>
                          </div>
                      </div>

                      <strong style="padding-left: 40px; padding-top: 10px;">Autonomous</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->AutoExitHabitat ?>" >
                              <label class="mdl-textfield__label" >Exit Habitat</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->AutoHatch ?>" >
                              <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->AutoCargo ?>" >
                              <label class="mdl-textfield__label" >Cargo Stored</label>
                          </div>
                      </div>

                      <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeleopHatch ?>" >
                              <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeleopCargo ?>" >
                              <label class="mdl-textfield__label" >Cargo Stored</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeleopRocketsComplete ?>" >
                              <label class="mdl-textfield__label" >Rockets Completed</label>
                          </div>
                      </div>

                      <strong style="padding-left: 40px; padding-top: 10px;">End Game</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->ReturnToHabitat ?>" >
                              <label class="mdl-textfield__label" >Returned To Habitat</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->Notes ?>" >
                              <label class="mdl-textfield__label" >Notes</label>
                          </div>
                      </div>
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
