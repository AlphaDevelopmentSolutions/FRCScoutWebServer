<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Matches.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");


$eventId = $_GET['eventId'];
$matchId = $_GET['matchId'];
$teamId = $_GET['teamId'];
$allianceColor = $_GET['allianceColor'];

$event = Events::withId($eventId);

$match = Matches::withId($matchId);

if(!empty($teamId))
    $team = Teams::withId($teamId);

?>

<!doctype html>
<html lang="en">
<head>

    <title><?php echo $match->toString() ?> Overview</title>
    <?php require_once('includes/meta.php') ?>

    <script src="<?php echo JS_URL ?>Chart.min.js"></script>
    <link rel="stylesheet" href="<?php echo CSS_URL ?>Chart.min.css">
    <script src="<?php echo JS_URL ?>chartjs-plugin-annotation.min.js"></script>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarArray = new NavBarArray();

    if(!empty($team))
    {
        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Teams', '/team-list.php?eventId=' . $event->BlueAllianceId);
        $navBarLinksArray[] = new NavBarLink('Team ' . $teamId, '/team-matches.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);
        $navBarArray[] = new NavBar($navBarLinksArray);
    }

    $navBarLinksArray = new NavBarLinkArray();
    if(empty($team))
        $navBarLinksArray[] = new NavBarLink('Matches', '/match-list.php?eventId=' . $event->BlueAllianceId);
    $navBarLinksArray[] = new NavBarLink($match->toString(), '', true);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Stats', '/match-stats.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key . ((!empty($team)) ? '&teamId=' . $team->Id : ''), true);
    $navBarLinksArray[] = new NavBarLink('Blue Alliance', '/match.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key . '&allianceColor=BLUE' . ((!empty($team)) ? '&teamId=' . $team->Id : ''));
    $navBarLinksArray[] = new NavBarLink('Red Alliance', '/match.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key . '&allianceColor=RED' . ((!empty($team)) ? '&teamId=' . $team->Id : ''));
    $navBarArray[] = new NavBar($navBarLinksArray);


    $header = new Header($event->Name, $additionContent, $navBarArray, $event, null, 'admin.php?yearId=' . $event->YearId);

    echo $header->toHtml();

    ?>
    <main class="mdl-layout__content">

        <div class="content-grid mdl-grid">

            <?php

            $keys = ScoutCardInfoKeys::getKeys(null, $event);

            $keyStates = array();

            foreach ($keys as $key)
                if($key->IncludeInStats)
                {
                    $keyStr = str_replace(' ', '', $key->KeyState);
                    $keyStates[$keyStr] = $key->KeyState;
                }

            foreach ($keyStates as $keyState => $placeholder)
            {
                $keyState = str_replace(' ', '', $keyState);
                ?>
                <div class="mdl-cell stats-cell">
                    <p><?php echo $placeholder ?></p>
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
        </div>

        <?php require_once('includes/footer.php') ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
<script defer src="<?php echo JS_URL ?>stat-charts.js.php?eventId=<?php echo $event->BlueAllianceId ?>&matchId=<?php echo $match->Key ?>"></script>
</body>
</html>
