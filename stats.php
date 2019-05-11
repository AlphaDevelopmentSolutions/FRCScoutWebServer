<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/Teams.php");
require_once(ROOT_DIR . "/classes/tables/Events.php");

$eventId = $_GET['eventId'];

$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>

    <title>Stats</title>
    <?php require_once('includes/meta.php') ?>

    <script src="<?php echo URL_PATH ?>/js/Chart.min.js"></script>
    <link rel="stylesheet" href="<?php echo URL_PATH ?>/css/Chart.min.css">
    <script src="<?php echo URL_PATH ?>/js/chartjs-plugin-annotation.min.js"></script>

</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Stats', '/stats.php?eventId=' . $event->BlueAllianceId, true);
    $navBarLinksArray[] = new NavBarLink('Legacy Stats', '/stats-legacy.php?eventId=' . $event->BlueAllianceId);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event);

    echo $header->toHtml();
    ?>

    <input id="eventId" hidden disabled value="<?php echo $event->BlueAllianceId ?>">


    <main class="mdl-layout__content">
        <div class="stats-search-wrapper">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label stats-search">
                <input id="teamSearch" class="mdl-textfield__input" type="text" placeholder="1114, 2056, 5885...">
                <label class="mdl-textfield__label">Search</label>
            </div>
        </div>
        <div class="content-grid mdl-grid">

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input" id="changeAutoItem" name="changeAutoItem">
                    </select>
                    <label class="mdl-textfield__label" for="changeAutoItem">Item</label>
                </div>
                <div class="stats-chart">
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
                <div class="stats-chart">
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
                <div class="stats-chart">
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
                <div class="stats-chart">
                    <canvas id="postGameChart"></canvas>
                </div>
            </div>

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="visibility: hidden">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input">
                    </select>
                </div>
                <div class="stats-chart">
                    <canvas id="dodBreakdownChart"></canvas>
                </div>
            </div>
        </div>
    </main>

</div>
<?php require_once('includes/bottom-scripts.php') ?>
<script defer src="<?php echo URL_PATH ?>/js/stat-charts.js"></script>
</body>
</html>
