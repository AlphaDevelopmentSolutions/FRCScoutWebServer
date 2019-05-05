<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/Teams.php");
require_once(ROOT_DIR . "/classes/Events.php");

$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];

$team = Teams::withId($teamId);
$event = Events::withId($eventId);
$pitCard = $team->getPitCards($event)[0];

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

    <title><?php echo $team->Id . ' - ' . $team->Name ?></title>
    <?php require_once('includes/meta.php') ?>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

        <?php
        $navBarArray = new NavBarArray();

        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Teams', '/team-list.php?eventId=' . $event->BlueAllianceId, false);
        $navBarLinksArray[] = new NavBarLink('Team ' . $teamId, '', true);

        $navBarArray[] = new NavBar($navBarLinksArray);

        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Matches', '/team-matches.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);
        $navBarLinksArray[] = new NavBarLink('Pits', '/team-pits.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, false);
        $navBarLinksArray[] = new NavBarLink('Photos', '/team-photos.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, false);

        $navBarArray[] = new NavBar($navBarLinksArray);

        $additionContent = '';

        $robotMedia = $team->getProfileImage();

        if(!empty($robotMedia->FileURI))
        {
            $additionContent .=
                '<div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                  <div class="circle-image" style="background-image: url(' . ROBOT_MEDIA_URL . $robotMedia->FileURI . ')">
                  </div>
                </div>';
        }

        $additionContent .=
        '
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            <h3>' . $team->Id . ' - ' . $team->Name . '</h3><br>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            <h3>' . $team->City . ', ' . $team->StateProvince . ', ' . $team->Country . '</h3><br>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">';


            if(!empty($team->FacebookURL))
            {
                $additionContent .=
                    '
                    <a target="_blank" href="https://www.facebook.com/' . $team->FacebookURL . '">
                        <i class="fab fa-facebook-f header-icon"></i>
                    </a>
                  ';
            }

            if(!empty($team->TwitterURL))
            {
                $additionContent .=
                    '
                    <a target="_blank" href="https://www.twitter.com/' . $team->TwitterURL . '">
                        <i class="fab fa-twitter header-icon"></i>
                    </a>
                  ';
            }

            if(!empty($team->InstagramURL))
            {
                $additionContent .=
                    '
                    <a target="_blank" href="https://www.instagram.com/' . $team->InstagramURL . '">
                        <i class="fab fa-instagram header-icon"></i>
                    </a>
                  ';
            }

            if(!empty($team->YoutubeURL))
            {
                $additionContent .=
                    '
                    <a target="_blank" href="https://www.youtube.com/' . $team->YoutubeURL . '">
                        <i class="fab fa-youtube header-icon"></i>
                    </a>
                  ';
            }

            if(!empty($team->WebsiteURL))
            {
                $additionContent .=
                    '
                    <a target="_blank" href="' . $team->WebsiteURL . '">
                        <i class="fas fa-globe header-icon"></i>
                    </a>
                  ';
            }

        $additionContent .=
        '
            </div>
            <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                <h6 style="margin: unset"><strong>OPR:</strong>' . round($opr, 2) . '</h6>
            </div>
    
            <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                <h6 style="margin: unset"><strong>DPR:</strong>' . round($dpr, 2) . '</h6>
            </div>
            <div id="quick-stats" style="padding-left: 40px" hidden>
                <h6 style="margin: unset"><strong>Drivetrain:</strong>' . $pitCard->DriveStyle . '</h6>
                <h6 style="margin: unset"><strong>Robot Weight:</strong>' . $pitCard->RobotWeight . '</h6>
                <h6 style="margin: unset"><strong>Robot Length:</strong>' . $pitCard->RobotLength . '</h6>
                <h6 style="margin: unset"><strong>Robot Width:</strong>' . $pitCard->RobotWidth . '</h6>
                <h6 style="margin: unset"><strong>Robot Height:</strong>' . $pitCard->RobotHeight . '</h6>
    
                <h6 style="margin: unset"><strong>Auto Exit Habitat:</strong>' . $pitCard->AutoExitHabitat . '</h6>
                <h6 style="margin: unset"><strong>Auto Hatch Panels:</strong>' . $pitCard->AutoHatch . '</h6>
                <h6 style="margin: unset"><strong>Auto Cargo:</strong>' . $pitCard->AutoCargo . '</h6>
    
                <h6 style="margin: unset"><strong>Teleop Hatch:</strong>' . $pitCard->TeleopHatch . '</h6>
                <h6 style="margin: unset"><strong>Teleop Cargo:</strong>' . $pitCard->TeleopCargo . '</h6>
    
                <h6 style="margin: unset"><strong>Return To Habitat:</strong>' . $pitCard->ReturnToHabitat . '</h6>
    
                <h6 style="margin: unset"><strong>Notes:</strong>' . $pitCard->Notes . '</h6>
                <h6 style="margin: unset"><strong>Completed By:</strong>' . $pitCard->CompletedBy . '</h6>
            </div>
            <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                <h6 style="margin: unset" ><a id="show-stats-btn" href="#" style="color:white" onclick="showQuickStats()">Show More</a></h6>
            </div>
            <div class="mdl-layout--large-screen-only mdl-layout__header-row"></div>';

        $header = new Header($event->Name, $additionContent, $navBarArray, $event->BlueAllianceId);

        echo $header->toHtml();

        ?>
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

          foreach($event->getMatches(null, $team) as $match)
          {
              $scoutCards = $match->getScoutCards($team);

              if(!empty($scoutCards))
                  echo $match->toHtml('/scout-card.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key . '&teamId=' . $team->Id . '&scoutCardId=' . $scoutCards[0]->Id, 'View Scout Card', $team->Id);

              else
                  echo $match->toHtml('/match.php?eventId=' . $match->EventId . '&matchId=' . $match->Key . '&allianceColor=BLUE', 'View Match Overview', $team->Id);
          }

          ?>
      </main>
    </div>
    <?php require_once('includes/bottom-scripts.php') ?>
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
