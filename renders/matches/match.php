<?php
require_once("../../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Matches.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfo.php");

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

        //get all teams for the match
        $teams = Teams::getObjects(null, $match);

        //iterate through the teams to display the cards to the page
        foreach($teams as $team)
        {
            //find the teams for the current selected alliance color
            switch($allianceColor)
            {
                case AllianceColors::BLUE:
                    if(
                            $team->Id == $match->BlueAllianceTeamOneId ||
                            $team->Id == $match->BlueAllianceTeamTwoId ||
                            $team->Id == $match->BlueAllianceTeamThreeId
                    )
                        $scoutCardInfoKeys = ScoutCardInfoKeys::toCard($event, $match, $team);
                    break;

                case AllianceColors::RED:
                    if(
                        $team->Id == $match->RedAllianceTeamOneId ||
                        $team->Id == $match->RedAllianceTeamTwoId ||
                        $team->Id == $match->RedAllianceTeamThreeId
                    )
                        $scoutCardInfoKeys = ScoutCardInfoKeys::toCard($event, $match, $team);
                    break;
            }
        }

         require_once(INCLUDES_DIR . 'footer.php') ?>
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
        var recordBeingModified = false;
        var saveableRecords = [];
        var modifiedElements = 0;
        var hasErrors = false;

        function deleteFailCallback(message)
        {
            showToast(message);
        }

        function deleteSuccessCallback(message)
        {
            location.reload();
        }

        function saveRecordOverride(recordType, recordId, recordDiv)
        {
            //check if the elements that have been modified are the total of elements to be modified
            if (modifiedElements === $(saveableRecords).length)
                recordBeingModified = false;

            //check if record is currently being modifid
            if (!recordBeingModified)
            {
                //reset vars
                saveableRecords = [];
                $(recordDiv).find('.scout-card-info-field').each(function ()
                {
                    //prevent blank items from being created
                    if($(this).val() !== "")
                        saveableRecords.push(this);
                });
                modifiedElements = 0;
                hasErrors = false;
                $('#save').attr('disabled', 'disabled');
                $('#delete').attr('disabled', 'disabled');
                $('#loading').removeAttr('hidden');

                //save each saveable record
                $(saveableRecords).each(function () {
                    //prevent blank items from being created
                    saveRecord(recordType, recordId, this);
                });
            }

            recordBeingModified = true;
        }

        function saveSuccessCallback(message)
        {
            //add count to modified elements
            modifiedElements++;

            //only show success toast on last element, and no errors
            if(modifiedElements === $(saveableRecords).length && !hasErrors)
                showToast(message);

            isFinished();
        }

        function saveFailCallback(message)
        {
            //specify there was errors and don't show save success
            hasErrors = true;

            //add count to modified elements
            modifiedElements++;

            //always show errors
            showToast(message);

            isFinished();
        }

        /**
         * Checks if the total elements to be modified have finished being modified
         */
        function isFinished()
        {
            if(modifiedElements === $(saveableRecords).length)
            {
                //if any of the elements are new elements with -1 id's, reload the page
                //this fixes any issues with the id not saving and duplicates being created
                $(saveableRecords).each(function ()
                {
                    if($(this).attr('info-id') == -1 && $(this).val() !== "")
                        location.reload();
                });

                $('#save').removeAttr('disabled');
                $('#delete').removeAttr('disabled');
                $('#loading').attr('hidden', 'hidden');
            }
        }
    </script>
    <?php
}
?>
</body>
</html>
