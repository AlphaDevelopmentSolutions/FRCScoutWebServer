<?php
require_once("../../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfo.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/core/Matches.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotMedia.php");

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
    $navBarLinksArray[] = new NavBarLink('Teams', TEAMS_URL . 'list?eventId=' . $event->BlueAllianceId);
    $navBarLinksArray[] = new NavBarLink('Team ' . $teamId, '', true);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Matches', TEAMS_URL . 'match-list?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
    $navBarLinksArray[] = new NavBarLink('Robot Info', TEAMS_URL . 'robot-info?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);
    $navBarLinksArray[] = new NavBarLink('Photos', TEAMS_URL . 'photos?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
    $navBarLinksArray[] = new NavBarLink('Stats', TEAMS_URL . 'stats?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $additionContent = '';

    $profileMedia = RobotMedia::getObjects(null, $event, $team);

    if (!empty($profileMedia))
    {
        $additionContent .=
            '<div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                  <div class="circle-image" style="background-image: url(' . ROBOT_MEDIA_THUMBS_URL . $profileMedia[sizeof($profileMedia) - 1]->FileURI . ')">

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

    $header = new Header($event->Name, $additionContent, $navBarArray, $event, null, ADMIN_URL . 'list?yearId=' . $event->YearId);

    echo $header->toHtml();

    ?>

    <input id="eventId" hidden disabled value="<?php echo $event->BlueAllianceId ?>">
    <input id="teamId" hidden disabled value="<?php echo $team->Id ?>">

    <main class="mdl-layout__content">

        <?php
            RobotInfoKeys::toCard($event, $team);
        ?>

        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
<?php require_once(INCLUDES_DIR . 'modals.php'); ?>
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

    function saveRecordOverride(recordType, recordId)
    {
        //check if the elements that have been modified are the total of elements to be modified
        if (modifiedElements === $(saveableRecords).length)
            recordBeingModified = false;

        //check if record is currently being modifid
        if (!recordBeingModified)
        {
            //reset vars
            saveableRecords = [];
            $('.robot-info-field').each(function ()
            {
                //prevent blank items from being created
                if(!($(this).attr('info-id') == '-1' && $(this).val() == ""))
                    saveableRecords.push(this);

            });
            modifiedElements = 0;
            hasErrors = false;
            $('#save').attr('disabled', 'disabled');
            $('#delete').attr('disabled', 'disabled');
            $('#loading').removeAttr('hidden');

            //save each saveable record
            $(saveableRecords).each(function () {
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
</body>
</html>
