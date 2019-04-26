<?php
require_once("config.php");
require_once("classes/PitCards.php");
require_once("classes/Events.php");

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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Pit Card <?php echo $pitCard->Id ?></title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.deep_purple-pink.min.css">
    <link rel="stylesheet" href="css/styles.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


      <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    </style>
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
        $navBarLinksArray[] = new NavBarLink('Teams', '/teams.php?eventId=' . $event->BlueAllianceId, false);
        $navBarLinksArray[] = new NavBarLink('Team ' . $pitCard->TeamId, '/team-pits.php?teamId=' . $pitCard->TeamId . '&eventId=' . $pitCard->EventId, false);
        $navBarLinksArray[] = new NavBarLink('Pit Card' . $pitCard->Id, '', true);


        $navBar = new NavBar($navBarLinksArray);

        $header = new Header($event->Name, null, $navBar, $event->BlueAllianceId);

        echo $header->toString();
        ?>
      <main class="mdl-layout__content">

          <?php require_once('includes/pit-card.php') ?>

          
          <div class="mdl-layout__tab-panel" id="stats">
<style>
.demo-card-wide.mdl-card {
  width: 60%;
/*    height: 1000px;*/
    margin: auto;
}
.demo-card-wide > .mdl-card__title {
  color: #fff;
  height: 176px;
/*  background: url('../assets/demos/welcome_card.jpg') center / cover;*/
    background-color: red;
                  }
.demo-card-wide > .mdl-card__menu {
  color: #fff;
}
</style>
              
          <section class="section--footer mdl-grid">
          </section>
        </div>

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
