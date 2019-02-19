<?php
require_once("config.php");
require_once("classes/Teams.php");
require_once("classes/Events.php");
require_once("classes/Matches.php");

$eventId = $_GET['eventId'];

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
    <title>Match Overview</title>

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

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.deep_purple-pink.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">



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
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            <h3><?php echo $event->Name; ?></h3>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
            <a href="/" class="mdl-layout__tab">Events</a>
            <a href="/teams.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab">Teams</a>
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
            <a href="/stats.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab ">Stats</a>
            <a href="/match-overview.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab is-active">Match Overview</a>
        </div>
    </header>
    <main class="mdl-layout__content">

        <?php

        foreach (Matches::getMatchIds($event->BlueAllianceId) as $matchId) {

            $blueAllianceScore = floor(Matches::getMatchBlueAllianceScore($event->BlueAllianceId, $matchId['MatchId'])[0]['BlueAllianceScore']);
            $redAllianceScore = floor(Matches::getMatchRedAllianceScore($event->BlueAllianceId, $matchId['MatchId'])[0]['RedAllianceScore']);

            $html =
                '
                <div class="mdl-layout__tab-panel is-active" id="overview">
                  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                      <div class="mdl-card__supporting-text">
                        <h4>Match ' . $matchId['MatchId'] . '</h4>
                        <div class="container">
                            <div class="row">
                                <div class="col-sm" ';

            $html .= (($blueAllianceScore > $redAllianceScore) ? 'style="font-weight: bold;"' : '') . '>
                                    Blue Alliance - ' . $blueAllianceScore;

            foreach (Teams::getBlueAllianceTeamsForMatch($matchId['MatchId']) AS $team)
            {
                $html .= '<div>' . $team['TeamId'] . '</div>';
            }
            $html .= '
                                </div>
                                <div class="col-sm" ';
            $html .= (($blueAllianceScore < $redAllianceScore) ? 'style="font-weight: bold;"' : '') . '>
                                    Red Alliance - ' . $redAllianceScore;

            foreach (Teams::getRedAllianceTeamsForMatch($matchId['MatchId']) AS $team)
            {
                $html .= '<div>' . $team['TeamId'] . '</div>';
            }

            $html .='                    </div>
                            </div>
                        </div>
                </div>
                      <div class="mdl-card__actions">
                        <a href="/match-overview-card.php?eventId=' . $eventId . '&matchId=' . $matchId['MatchId'] . '&allianceColor=BLUE" class="mdl-button">View</a>
                      </div>
                    </div>
                  </section>
                </div>
            ';

            echo $html;
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
</body>
</html>
