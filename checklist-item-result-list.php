<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Matches.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItems.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItemResults.php");

$eventId = $_GET['eventId'];
$matchId = $_GET['matchId'];

$event = Events::withId($eventId);

if(!empty($matchId))
    $match = Matches::withId($matchId);
?>

<!doctype html>
<html lang="en">
<head>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
    <title>Checklist Item Results<?php echo ((!empty($match)) ? ' - ' . $match->toString() : '') ?></title>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Checklist Items', 'checklist-item-list.php?eventId=' . $event->BlueAllianceId);
    $navBarLinksArray[] = new NavBarLink('Completed Checklist Items', 'checklist-item-result-list.php?eventId=' . $event->BlueAllianceId, ((empty($match)) ? true : false));

    if(!empty($match))
        $navBarLinksArray[] = new NavBarLink('Completed Checklist Items - ' . $match->toString(), '', true);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event, null, 'admin.php?yearId=' . $event->YearId);

    echo $header->toHtml();
    ?>
    <main class="mdl-layout__content">

        <?php

        //no match selected, show match list
        if(empty($match))
        {
            foreach ($event->getMatches(null, Teams::withId(getCoreAccount()->TeamId)) as $match)
                echo $match->toHtml('checklist-item-result-list.php?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key, 'View Completed Checklist Items', getCoreAccount()->TeamId);
        }

        //match selected, show checklist item results for specified match
        else
        {
            foreach(ChecklistItemResults::getObjects($match) as $checklistItemResult)
            {
                $checklistItemResult->toHtml();
            }
        }



        ?>

        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
<?php
if(!empty($match))
{
    require_once(INCLUDES_DIR . 'modals.php');
    ?>
<script src="<?php echo JS_URL ?>modify-record.js.php"></script>
<script>

    var pendingRowRemoval = [];

    function deleteRecordOverride(row, recordType, recordId)
    {
        pendingRowRemoval.push($(row));
        deleteRecord(recordType, recordId);
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
