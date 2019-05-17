<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/ScoutCards.php");
require_once(ROOT_DIR . "/classes/tables/Events.php");
require_once(ROOT_DIR . "/classes/tables/Matches.php");

$scoutCardId = $_GET['scoutCardId'];
$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];
$matchId = $_GET['matchId'];
$action = (isset($_POST['save'])) ? 'save' : ((isset($_POST['delete'])) ? 'delete' : '');


if(!empty($scoutCardId))
{
    $scoutCard = ScoutCards::withId($scoutCardId);
}
else
{
    $scoutCard = new ScoutCards();
    $scoutCard->TeamId = $teamId;
    $scoutCard->EventId = $eventId;
    $scoutCard->MatchId = $matchId;
}


$match = Matches::withId($scoutCard->MatchId);
$event = Events::withId($scoutCard->EventId);

if(isPostBack() && loggedIn())
{
    if($action == 'save') {

        $scoutCard->CompletedBy = $_POST['completedBy'];
        $scoutCard->MatchId = $_POST['matchId'];
        $scoutCard->BlueAllianceFinalScore = $_POST['blueAllianceScore'];
        $scoutCard->RedAllianceFinalScore = $_POST['redAllianceScore'];
        $scoutCard->AutonomousExitHabitat = $_POST['autonomousExitHabitat'];
        $scoutCard->AutonomousHatchPanelsSecured = $_POST['autonomousHatchPanelsSecured'];
        $scoutCard->AutonomousHatchPanelsSecuredAttempts = $_POST['autonomousHatchPanelsSecuredAttempts'];
        $scoutCard->AutonomousCargoStored = $_POST['autonomousCargoStored'];
        $scoutCard->AutonomousCargoStoredAttempts = $_POST['autonomousCargoStoredAttempts'];
        $scoutCard->TeleopHatchPanelsSecured = $_POST['teleopHatchPanelsSecured'];
        $scoutCard->TeleopHatchPanelsSecuredAttempts = $_POST['teleopHatchPanelsSecuredAttempts'];
        $scoutCard->TeleopCargoStored = $_POST['teleopCargoStored'];
        $scoutCard->TeleopCargoStoredAttempts = $_POST['teleopCargoStoredAttempts'];
        $scoutCard->EndGameReturnedToHabitat = $_POST['returnedToHabitat'];
        $scoutCard->EndGameReturnedToHabitatAttempts = $_POST['returnedToHabitatAttempts'];
        $scoutCard->Notes = $_POST['notes'];

        $actionSuccess = $scoutCard->save();
    }
    else if($action == 'delete')
    {
        if($scoutCard->delete())
            header('Location: ' . URL_PATH . '/team-matches.php?teamId=' . $scoutCard->TeamId . '&eventId=' . $scoutCard->EventId);
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <title><?php echo $match->toString() ?></title>
    <?php require_once('includes/meta.php') ?>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
  <div id="demo-toast-example" class="mdl-js-snackbar mdl-snackbar">
      <div class="mdl-snackbar__text"></div>
      <button class="mdl-snackbar__action" type="button"></button>
  </div>
  <dialog class="mdl-dialog">
      <h4 class="mdl-dialog__title">Are you sure?</h4>
      <div class="mdl-dialog__content">
          <p>
              Are you sure you want to delete this scout card?
          </p>
      </div>
      <div class="mdl-dialog__actions">
          <button type="button" class="mdl-button negative">No</button>
          <button type="button" class="mdl-button positive">Yes</button>
      </div>
  </dialog>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <?php
        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Teams', '/team-list.php?eventId=' . $event->BlueAllianceId);
        $navBarLinksArray[] = new NavBarLink('Team ' . $scoutCard->TeamId, '/team-matches.php?teamId=' . $scoutCard->TeamId . '&eventId=' . $scoutCard->EventId);
        $navBarLinksArray[] = new NavBarLink($match->toString(), '', true);


        $navBar = new NavBar($navBarLinksArray);

        $header = new Header($event->Name, null, $navBar, $event);

        echo $header->toHtml();
        ?>
      <main class="mdl-layout__content">

          <?php
          echo $scoutCard->toHtml();
          ?>
      </main>
    </div>
  <?php require_once('includes/bottom-scripts.php') ?>

    <?php

    if(isPostBack() && loggedIn())
    {
        if($action == 'save')
            if($actionSuccess)
                $message = "Successfully Saved!";
            else
                $message = "Save Failed!";
        else if($action == 'delete')
            if($actionSuccess)
                $message = "Deletion Successful!";
            else
                $message = "Deletion Failed!";
        echo
        "<script>
            $(document).ready(function()
            {
                'use strict';
                window['counter'] = 0;
                var snackbarContainer = document.querySelector('#demo-toast-example');
                var showToastButton = document.querySelector('#demo-show-toast');
        
                'use strict';
                var data = {message: '" . $message . "'};
                     snackbarContainer.MaterialSnackbar.showSnackbar(data);
                 });
          </script>";
    }

    if(loggedIn())
    {
        echo
        "<script>
            function confirmDelete()
            {
                var dialog = document.querySelector('dialog');
                var showDialogButton = document.querySelector('#show-dialog');
                if (! dialog.showModal) {
                    dialogPolyfill.registerDialog(dialog);
                }
                dialog.showModal();
                
                dialog.querySelector('.negative').addEventListener('click', function() {
                    dialog.close();
                });
                
                dialog.querySelector('.positive').addEventListener('click', function() {
                    $('#delete').click();
                });
            }
            
          </script>";
    }

    ?>

  </body>
</html>
