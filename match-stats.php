<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/Events.php");
require_once(ROOT_DIR . "/classes/Matches.php");
require_once(ROOT_DIR . "/classes/Teams.php");

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

    <script src="<?php echo URL_PATH ?>/js/Chart.min.js"></script>
    <link rel="stylesheet" href="<?php echo URL_PATH ?>/css/Chart.min.css">
    <script src="<?php echo URL_PATH ?>/js/chartjs-plugin-annotation.min.js"></script>
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


    $header = new Header($event->Name, $additionContent, $navBarArray, $event);

    echo $header->toHtml();

    ?>
    <main class="mdl-layout__content">

        <input id="eventId" hidden disabled value="<?php echo $event->BlueAllianceId ?>">
        <input id="matchId" hidden disabled value="<?php echo $match->Key ?>">

        <div class="content-grid mdl-grid">

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input" id="changeAutoItem" name="changeAutoItem">
                    </select>
                    <label class="mdl-textfield__label" for="changeAutoItem">Item</label>
                </div>
                <div class="team-stats-chart">
                    <canvas id="autoChart"></canvas>
                </div>
            </div>

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input" id="changeTeleopItem" name="changeTeleopItem">
                    </select>
                    <label class="mdl-textfield__label" for="changeTeleopItem">Item</label>
                </div>
                <div class="team-stats-chart">
                    <canvas id="teleopChart"></canvas>
                </div>
            </div>

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input" id="changeEndGameItem" name="changeEndGameItem">
                    </select>
                    <label class="mdl-textfield__label" for="changeEndGameItem">Item</label>
                </div>
                <div class="team-stats-chart">
                    <canvas id="endGameChart"></canvas>
                </div>
            </div>

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input" id="changePostGameItem" name="changePostGameItem">
                    </select>
                    <label class="mdl-textfield__label" for="changePostGameItem">Item</label>
                </div>
                <div class="team-stats-chart">
                    <canvas id="postGameChart"></canvas>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
<script defer src="<?php echo URL_PATH ?>/js/stat-charts.js"></script>
</body>
</html>