<?php
require_once("../../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Matches.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");

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
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarArray = new NavBarArray();

    if(!empty($team))
    {
        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Teams', TEAMS_URL . 'list?eventId=' . $event->BlueAllianceId);
        $navBarLinksArray[] = new NavBarLink('Team ' . $teamId, TEAMS_URL . 'match-list?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);
        $navBarArray[] = new NavBar($navBarLinksArray);
    }

    $navBarLinksArray = new NavBarLinkArray();
    if(empty($team))
        $navBarLinksArray[] = new NavBarLink('Matches', MATCHES_URL . 'list?eventId=' . $event->BlueAllianceId);
    $navBarLinksArray[] = new NavBarLink($match->toString(), '', true);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Stats', MATCHES_URL . 'stats?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key . ((!empty($team)) ? '&teamId=' . $team->Id : ''));
    $navBarLinksArray[] = new NavBarLink('Blue Alliance', MATCHES_URL . 'match?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key . '&allianceColor=BLUE' . ((!empty($team)) ? '&teamId=' . $team->Id : ''), ($allianceColor == 'BLUE'));
    $navBarLinksArray[] = new NavBarLink('Red Alliance', MATCHES_URL . 'match?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key . '&allianceColor=RED' . ((!empty($team)) ? '&teamId=' . $team->Id : ''), ($allianceColor == 'RED'));

    $navBarArray[] = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, $additionContent, $navBarArray, $event, null, ADMIN_URL . 'list?yearId=' . $event->YearId);

    echo $header->toHtml();

    ?>
    <main class="mdl-layout__content">

        <?php
        //get all scout cards from match
        $scoutCardInfoArray = $match->getScoutCards();

        foreach($scoutCardInfoArray as $scoutCardInfo)
        {
            $arr = new ScoutCardInfoArray();

            foreach($scoutCardInfo as $testVal)
                $arr[] = $testVal;

            if ($allianceColor == $match->getAllianceColor(Teams::withId($arr[0]->TeamId)))
                $arr->toHtml();
        }

        ?>

        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
<?php
if(!empty($allianceColor))
{
    require_once(INCLUDES_DIR . 'modals.php');
    ?>
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
    <?php
}
?>
</body>
</html>
