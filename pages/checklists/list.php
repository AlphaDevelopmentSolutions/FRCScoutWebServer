<?php
require_once("../../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/dmuy/MDTimePicker/mdtimepicker.min.css">
    <script src="https://cdn.jsdelivr.net/gh/dmuy/MDTimePicker/mdtimepicker.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/dmuy/duDatepicker/duDatepicker.min.css">
    <script src="https://cdn.jsdelivr.net/gh/dmuy/duDatepicker/duDatepicker.min.js"></script>
    <title>Checklist Item Results<?php echo ((!empty($match)) ? ' - ' . $match->toString() : '') ?></title>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Completed Checklist Items', CHECKLISTS_URL . 'list?eventId=' . $event->BlueAllianceId, ((empty($match)) ? true : false));

    if(!empty($match))
        $navBarLinksArray[] = new NavBarLink('Completed Checklist Items - ' . $match->toString(), '', true);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event, null, ADMIN_URL . 'list?yearId=' . $event->YearId);

    echo $header->toHtml();
    ?>
    <main class="mdl-layout__content">

        <?php

        //no match selected, show match list
        if(empty($match))
        {
            foreach ($event->getMatches(null, Teams::withId(getCoreAccount()->TeamId)) as $match)
                echo $match->toHtml(CHECKLISTS_URL . 'list?eventId=' . $event->BlueAllianceId . '&matchId=' . $match->Key, 'View Completed Checklist Items', getCoreAccount()->TeamId);
        }

        //match selected, show checklist item results for specified match
        else
        {
            $checklistItems = ChecklistItems::getObjects(Years::withId($event->YearId));
            $checklistItemResults = ChecklistItemResults::getObjects($match);

            foreach ($checklistItems as $checklistItem)
            {
                $resultFound = false;

                foreach ($checklistItemResults as $checklistItemResult)
                {
                    if($checklistItemResult->ChecklistItemId == $checklistItem->Id && !$resultFound)
                    {
                        $resultFound = true;
                        $checklistItemResult->toHtml();
                    }
                }

                if(!$resultFound)
                {
                    $checklistItemResult = new ChecklistItemResults();
                    $checklistItemResult->MatchId = $match->Key;
                    $checklistItemResult->ChecklistItemId = $checklistItem->Id;
                    $checklistItemResult->Status = ChecklistItemResults::INCOMPLETE;
                    $checklistItemResult->toHtml();
                }
            }
        }
        ?>

        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php');
if(!empty($match))
{
    require_once(INCLUDES_DIR . 'modals.php');
    if(getUser()->IsAdmin == 1)
    {
    ?>
    <script src="<?php echo JS_URL ?>modify-record.js.php"></script>
    <?php
    }
?>
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

    function saveSuccessCallback(message)
    {
        location.reload();
    }

    function saveFailCallback(message)
    {
        showToast(message);
    }

    <?php
    if(getUser()->IsAdmin == 1)
    {
    ?>
    $(document).ready(function ()
    {
        $('.CompletedTime').mdtimepicker({
            format: 'hh:mm'
        });

        $('.CompletedDate').duDatepicker({
            format: 'yyyy-mm-dd'
        });

        $(".datatype-menu-item").click(function ()
        {
            var value = $(this).attr("value");
            var inputField = $(this).parent().parent().parent().find('input')[0];

            $(inputField).attr("value", value);

            if (value == "<?php echo Status::COMPLETE ?>")
            {
                $(inputField).addClass("good");
                $(inputField).removeClass("bad");
            }

            else if (value == "<?php echo Status::INCOMPLETE ?>")
            {
                $(inputField).addClass("bad");
                $(inputField).removeClass("good");
            }
        });
    });
    <?php
    }
    ?>
</script>
<?php
}
?>
</body>
</html>
