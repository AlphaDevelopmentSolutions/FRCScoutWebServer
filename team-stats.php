<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/Teams.php");
require_once(ROOT_DIR . "/classes/tables/Events.php");
require_once(ROOT_DIR . "/classes/tables/ScoutCardInfoKeys.php");


$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];

$team = Teams::withId($teamId);
$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo $team->Id . ' - ' . $team->Name ?> - Stats</title>
    <?php require_once('includes/meta.php') ?>
    <script src="<?php echo URL_PATH ?>/js/Chart.min.js"></script>
    <link rel="stylesheet" href="<?php echo URL_PATH ?>/css/Chart.min.css">
    <script src="<?php echo URL_PATH ?>/js/chartjs-plugin-annotation.min.js"></script>
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
    $navBarLinksArray[] = new NavBarLink('Robot Info', '/team-robot-info.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
    $navBarLinksArray[] = new NavBarLink('Photos', '/team-photos.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
    $navBarLinksArray[] = new NavBarLink('Stats', '/team-stats.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);

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
        '</div>';

    $header = new Header($event->Name, $additionContent, $navBarArray, $event);

    echo $header->toHtml();

    ?>

    <input id="eventId" hidden disabled value="<?php echo $event->BlueAllianceId ?>">
    <input id="teamId" hidden disabled value="<?php echo $team->Id ?>">

    <main class="mdl-layout__content">

        <div class="content-grid mdl-grid">

            <?php

            $keys = ScoutCardInfoKeys::getKeys(null, $event);

            $keyStates = array();

            foreach ($keys as $key)
                if($key->IncludeInStats)
                {
                    $keyStr = str_replace(' ', '', $key->KeyState);
                    $keyStates[$keyStr] = 'placeholder';
                }

            foreach ($keyStates as $keyState => $placeholder)
            {
                $keyState = str_replace(' ', '', $keyState);
                ?>
                <div class="mdl-cell stats-cell">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                        <select class="mdl-textfield__input" id="<?php echo 'change' . $keyState . 'Item' ?>" name="<?php echo 'change' . $keyState . 'Item' ?>">
                        </select>
                        <label class="mdl-textfield__label" for="<?php echo 'change' . $keyState . 'Item' ?>">Item</label>
                    </div>
                    <div class="stats-chart">
                        <canvas id="<?php echo $keyState . 'Chart' ?>"></canvas>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="mdl-cell stats-cell" id="oprDprStats">
                <h6>OPR / DPR</h6>
                <div>
                    <h3>OPR: <span id="opr">0</span></h3><br>
                    <h3>DPR: <span id="dpr">0</span></h3>
                </div>
            </div>
        </div>

        <?php require_once('includes/footer.php') ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
<script defer src="<?php echo URL_PATH ?>/js/stat-charts.js.php?eventId=<?php echo $event->BlueAllianceId ?>"></script>
<script defer src="<?php echo URL_PATH ?>/js/get-opr.js"></script>
</body>
</html>
