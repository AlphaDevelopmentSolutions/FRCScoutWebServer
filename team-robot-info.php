<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfo.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/core/Matches.php");


$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];

$team = Teams::withId($teamId);
$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo $team->Id . ' - ' . $team->Name ?> - Pits</title>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarArray = new NavBarArray();

    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Teams', 'team-list.php?eventId=' . $event->BlueAllianceId);
    $navBarLinksArray[] = new NavBarLink('Team ' . $teamId, '', true);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Matches', 'team-matches.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
    $navBarLinksArray[] = new NavBarLink('Robot Info', 'team-robot-info.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);
    $navBarLinksArray[] = new NavBarLink('Photos', 'team-photos.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
    $navBarLinksArray[] = new NavBarLink('Stats', 'team-stats.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $additionContent = '';

    $robotMedia = $team->getProfileImage(Years::withId($event->YearId));

    if (!empty($robotMedia->FileURI))
    {
        $additionContent .=
            '<div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                  <div class="circle-image" style="background-image: url(' . ROBOT_MEDIA_THUMBS_URL . $robotMedia->FileURI . ')">

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
        '</div>';

    $header = new Header($event->Name, $additionContent, $navBarArray, $event, null, 'admin.php?yearId=' . $event->YearId);

    echo $header->toHtml();

    ?>

    <input id="eventId" hidden disabled value="<?php echo $event->BlueAllianceId ?>">
    <input id="teamId" hidden disabled value="<?php echo $team->Id ?>">

    <main class="mdl-layout__content">

        <?php

            $array = RobotInfo::getObjects(null, null, $event, $team);

            $array->toHtml();
        ?>

        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
<?php require_once(INCLUDES_DIR . 'modals.php'); ?>
<script src="<?php echo JS_URL ?>modify-record.js.php"></script>
<script>
    function deleteFailCallback(message)
    {
        showToast(message);
    }

    function deleteSuccessCallback(message)
    {
        location.reload();
    }
</script>
</body>
</html>
