<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/Teams.php");
require_once(ROOT_DIR . "/classes/Events.php");


$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];

$team = Teams::withId($teamId);
$event = Events::withId($eventId);
$pitCard = $team->getPitCards($event)[0];
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
    $navBarLinksArray[] = new NavBarLink('Teams', '/team-list.php?eventId=' . $event->BlueAllianceId);
    $navBarLinksArray[] = new NavBarLink('Team ' . $teamId, '', true);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Matches', '/team-matches.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
    $navBarLinksArray[] = new NavBarLink('Pits', '/team-pits.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
    $navBarLinksArray[] = new NavBarLink('Photos', '/team-photos.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);
    $navBarLinksArray[] = new NavBarLink('Stats', '/team-stats.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $additionContent = '';

    $profileMedia = $team->getProfileImage();

    if (!empty($profileMedia->FileURI))
    {
        $additionContent .=
            '<div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                  <div class="circle-image" style="background-image: url(' . ROBOT_MEDIA_URL . $profileMedia->FileURI . ')">

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


    if (!empty($team->FacebookURL))
    {
        $additionContent .=
            '
                    <a target="_blank" href="https://www.facebook.com/' . $team->FacebookURL . '">
                        <i class="fab fa-facebook-f header-icon"></i>
                    </a>
                  ';
    }

    if (!empty($team->TwitterURL))
    {
        $additionContent .=
            '
                    <a target="_blank" href="https://www.twitter.com/' . $team->TwitterURL . '">
                        <i class="fab fa-twitter header-icon"></i>
                    </a>
                  ';
    }

    if (!empty($team->InstagramURL))
    {
        $additionContent .=
            '
                    <a target="_blank" href="https://www.instagram.com/' . $team->InstagramURL . '">
                        <i class="fab fa-instagram header-icon"></i>
                    </a>
                  ';
    }

    if (!empty($team->YoutubeURL))
    {
        $additionContent .=
            '
                    <a target="_blank" href="https://www.youtube.com/' . $team->YoutubeURL . '">
                        <i class="fab fa-youtube header-icon"></i>
                    </a>
                  ';
    }

    if (!empty($team->WebsiteURL))
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
                <h6 style="margin: unset"><strong>OPR:</strong><span id="opr">0</span></h6>
            </div>
    
            <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                <h6 style="margin: unset"><strong>DPR:</strong><span id="dpr">0</span></h6>
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

    $header = new Header($event->Name, $additionContent, $navBarArray, $event);

    echo $header->toHtml();

    ?>
    <input id="eventId" hidden disabled value="<?php echo $event->BlueAllianceId ?>">
    <input id="teamId" hidden disabled value="<?php echo $team->Id ?>">

    <main class="mdl-layout__content">

        <?php

        foreach ($team->getRobotPhotos() as $robotMedia)
            echo $robotMedia->toHtml();

        ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
<script defer src="<?php echo URL_PATH ?>/js/quick-stats-toggle.js"></script>
<script defer src="<?php echo URL_PATH ?>/js/get-opr.js"></script>

</body>
</html>
