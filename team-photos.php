<?php
require_once("config.php");
require_once("classes/Teams.php");
require_once("classes/PitCards.php");
require_once("classes/Events.php");


$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];

$team = Teams::withId($teamId);
$event = Events::withId($eventId);
$pitCard = PitCards::withId(PitCards::getNewestPitCard($team->Id, $event->BlueAllianceId)['0']['Id']);

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
        $navBarLinksArray[] = new NavBarLink('Matches', '/team-matches.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, false);
        $navBarLinksArray[] = new NavBarLink('Pits', '/team-pits.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, false);
        $navBarLinksArray[] = new NavBarLink('Photos', '/team-photos.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);

        $navBarArray[] = new NavBar($navBarLinksArray);

        $additionContent = '';

        $robotMediaUri = Teams::getProfileImageUri($team->Id);

        if(!empty($robotMediaUri))
        {
            $robotMediaUri = ROBOT_MEDIA_URL . $robotMediaUri;
            $additionContent .=
                '<div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                  <div class="circle-image" style="background-image: url(' . $robotMediaUri . ')">

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

        echo $header->toString();

        ?>
      <main class="mdl-layout__content">

          <?php

          foreach(Teams::getRobotPhotos($teamId) as $robotPhotoUri)
          {
              $robotMediaUri = ROBOT_MEDIA_URL . $robotPhotoUri;

          ?>
                <div class="mdl-layout__tab-panel is-active" id="overview">
                  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                      <div class="mdl-card__supporting-text">
                        <img class="robot-media" src="<?php echo $robotMediaUri ?>"  height="350"/>
                      </div>
                       <div class="mdl-card__actions">
                        <a target="_blank" href="<?php echo $robotMediaUri ?>" class="mdl-button">View</a>
                      </div>
                    </div>
                  </section>
                </div>
        <?php
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
