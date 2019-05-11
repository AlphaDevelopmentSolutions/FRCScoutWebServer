<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/PitCards.php");
require_once(ROOT_DIR . "/classes/tables/Events.php");

$pitCardId = $_GET['pitCardId'];
$action = (isset($_POST['save'])) ? 'save' : ((isset($_POST['delete'])) ? 'delete' : '');

$pitCard = PitCards::withId($pitCardId);

$event = Events::withId($pitCard->EventId);


if(isPostBack() && loggedIn())
{
    if($action == 'save') {

        $pitCard->CompletedBy = $_POST['completedBy'];
        $pitCard->DriveStyle = $_POST['driveStyle'];
        $pitCard->AutoExitHabitat = $_POST['autonomousExitHabitat'];
        $pitCard->AutoHatch = $_POST['autonomousHatchPanelsSecured'];
        $pitCard->AutoCargo = $_POST['autonomousCargoStored'];
        $pitCard->TeleopHatch = $_POST['teleopHatchPanelsSecured'];
        $pitCard->TeleopCargo = $_POST['teleopCargoStored'];
        $pitCard->ReturnToHabitat = $_POST['returnedToHabitat'];
        $pitCard->Notes = $_POST['notes'];

        $actionSuccess = $pitCard->save();
    }
    else if($action == 'delete')
    {
        if($pitCard->delete())
            header('Location: http://scouting.wiredcats5885.ca/team-matches.php?teamId=' . $pitCard->TeamId . '&eventId=' . $pitCard->EventId);
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
      <?php require_once('includes/meta.php') ?>
      <title><?php echo $pitCard->TeamId ?> - Pit Card</title>
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
        $navBarLinksArray[] = new NavBarLink('Team ' . $pitCard->TeamId, '/team-robot-info.php?teamId=' . $pitCard->TeamId . '&eventId=' . $pitCard->EventId);
        $navBarLinksArray[] = new NavBarLink($pitCard->TeamId . ' - Pit Card', '', true);


        $navBar = new NavBar($navBarLinksArray);

        $header = new Header($event->Name, null, $navBar, $event);

        echo $header->toHtml();
        ?>
      <main class="mdl-layout__content">

          <?php
          echo $pitCard->toHtml();
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
