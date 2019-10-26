<?php
require_once("../../config.php");
require_once(ROOT_DIR . "/classes/Ajax.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotMedia.php");

$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];

$team = Teams::withId($coreDb, $teamId);
$event = Events::withId($coreDb, $eventId);

//robot media submission
if(isPostBack() && !empty($_FILES) && !empty(getUser()))
{
    $file = $_FILES['RobotMedia'];

    //verify the image is the correct type
    if($file['type'] == 'image/jpeg')
    {
        //create and save the robot media
        $robotMedia = new RobotMedia();
        $robotMedia->YearId = $event->YearId;
        $robotMedia->EventId = $event->BlueAllianceId;
        $robotMedia->TeamId = $team->Id;
        $robotMedia->FileURI = base64_encode(file_get_contents($file['tmp_name']));

        $mediaSaveSuccess = $robotMedia->save($localDb);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo $team->Id . ' - ' . $team->Name ?> - Photos</title>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
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
    $navBarLinksArray[] = new NavBarLink('Robot Info', TEAMS_URL . 'robot-info?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
    $navBarLinksArray[] = new NavBarLink('Photos', TEAMS_URL . 'photos?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);
    $navBarLinksArray[] = new NavBarLink('Stats', TEAMS_URL . 'stats?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $additionContent = '';

    $profileMedia = RobotMedia::getObjects($localDb, null, $event, $team);

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
        foreach($profileMedia as $robotMedia)
            $robotMedia->toHtml()
        ?>
        <?php require_once(INCLUDES_DIR . 'footer.php') ?>

        <?php
        if(!empty(getUser())) {
            ?>
            <button onclick="$('#RobotMedia').trigger('click');"
                    class="settings-fab mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                <i class="material-icons">add</i>
            </button>

            <form id="robot-media-form" method="post" action="" class="hide" enctype="multipart/form-data">
                <input onchange="uploadImage(this)" type="file" name="RobotMedia" id="RobotMedia" accept="image/jpeg">
            </form>
            <?php
        }
        ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
<?php require_once(INCLUDES_DIR . 'modals.php'); ?>
<script src="<?php echo JS_URL ?>modify-record.js.php"></script>
<script>
    function deleteSuccessCallback(message)
    {
        location.reload();
    }

    function deleteFailCallback(message)
    {
        showToast(message);
    }

    <?php
    if(!empty(getUser())) {
    ?>
    /**
     * Uploads image to server
     */
    function uploadImage(file) {
        if (file.files && file.files[0] && file.files[0].type === 'image/jpeg') {
            showDialog("Upload image?", "Are you sure you would like to upload this robot media?", function () {
                $('#robot-media-form').submit();
            });
        }

        else
            showToast('Robot media must be a .JPG or .JPEG.')
    }
    <?php
    }

    if($mediaSaveSuccess === true)
    {
    ?>
    showToast('Media saved successfully!');
    <?php
    }
    else if($mediaSaveSuccess === false)
    {
    ?>
    showToast('Invalid file format. Please submit a JPEG/JPG image.');
    <?php
    }
    ?>
</script>
</body>
</html>
