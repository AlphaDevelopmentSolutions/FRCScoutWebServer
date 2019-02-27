<?php
require_once("config.php");
require_once("classes/PitCards.php");
require_once("classes/Events.php");

$pitCardId = $_GET['pitCardId'];
$action = (isset($_POST['save'])) ? 'save' : ((isset($_POST['delete'])) ? 'delete' : '');

$pitCard = new PitCards();
$pitCard->load($pitCardId);

$event = new Events();
$event->load($pitCard->EventId);


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
        $pitCard->TeleopRocketsComplete = $_POST['teleopRocketsCompleted'];
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
      <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            <?php include_once('includes/login-form.php') ?>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
          <h3>Pit Card <?php echo $pitCard->Id ?></h3>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
          <a href="/" class="mdl-layout__tab">Events</a>
          <a href="/teams.php?eventId=<?php echo $pitCard->EventId; ?>" class="mdl-layout__tab ">Teams</a>
          <a href="/team-pits.php?teamId=<?php echo $pitCard->TeamId?>&eventId=<?php echo $pitCard->EventId;?>" class="mdl-layout__tab">Team <?php echo $pitCard->TeamId; ?></a>
          <a href="" class="mdl-layout__tab is-active">Pit Card <?php echo $pitCard->Id; ?></a>
        </div>
      </header>
      <main class="mdl-layout__content">

          <div class="mdl-layout__tab-panel is-active" id="overview">
              <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                  <div class="mdl-card mdl-cell mdl-cell--12-col">
                      <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" style="padding-top: 30px;" id="pit-card-form">
                      <strong style="padding-left: 40px; padding-top: 10px;">Pre Game</strong>
                      <div class="mdl-card__supporting-text">

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeamId ?>" >
                              <label class="mdl-textfield__label" >Team Id</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->CompletedBy ?>" name="completedBy">
                              <label class="mdl-textfield__label" >Scouter</label>
                          </div>
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->DriveStyle ?>" name="driveStyle">
                              <label class="mdl-textfield__label" >Drive Style</label>
                          </div>
                      </div>

                      <strong style="padding-left: 40px; padding-top: 10px;">Autonomous</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->AutoExitHabitat ?>" name="autonomousExitHabitat">
                              <label class="mdl-textfield__label" >Exit Habitat</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->AutoHatch ?>" name="autonomousHatchPanelsSecured">
                              <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->AutoCargo ?>" name="autonomousCargoStored">
                              <label class="mdl-textfield__label" >Cargo Stored</label>
                          </div>
                      </div>

                      <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeleopHatch ?>" name="teleopHatchPanelsSecured">
                              <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeleopCargo ?>" name="teleopCargoStored">
                              <label class="mdl-textfield__label" >Cargo Stored</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeleopRocketsComplete ?>" name="teleopRocketsCompleted">
                              <label class="mdl-textfield__label" >Rockets Completed</label>
                          </div>
                      </div>

                      <strong style="padding-left: 40px; padding-top: 10px;">End Game</strong>
                      <div class="mdl-card__supporting-text">
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->ReturnToHabitat ?>" name="returnedToHabitat">
                              <label class="mdl-textfield__label" >Returned To Habitat</label>
                          </div>

                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                              <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->Notes ?>" name="notes">
                              <label class="mdl-textfield__label" >Notes</label>
                          </div>
                      </div>
                      <?php

                      if(loggedIn()) {
                          echo
                          '<div class="mdl-card__supporting-text">
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <div class="mdl-card__supporting-text">
                                      <div class="mdl-card__supporting-text" style="margin-bottom: 30px;">
                                          <button name="save" type="submit" class="mdl-button mdl-js-button mdl-button--raised">
                                            Save
                                          </button>
                                      </div>
                                  </div>
                              </div>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <div class="mdl-card__supporting-text">
                                      <div class="mdl-card__supporting-text" style="margin-bottom: 30px;">
                                          <button onclick="confirmDelete()" type="button" class="mdl-button mdl-js-button mdl-button--raised">
                                            Delete
                                          </button>
                                      </div>
                                  </div>
                              </div>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <div class="mdl-card__supporting-text">
                                      <div hidden class="mdl-card__supporting-text" style="margin-bottom: 30px;">
                                          <button id="delete" name="delete" type="submit" class="mdl-button mdl-js-button mdl-button--raised">
                                          </button>
                                      </div>
                                  </div>
                              </div>
                          </div>';
                      }

                      ?>
                    </form>
                  </div>
              </section>
          </div>

          
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
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
