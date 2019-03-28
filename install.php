<?php
require_once("config.php");

if(isPostBack())
    $error = install();
else if (file_exists('classes/Keys.php'))
    header('Location: ' . URL_PATH);


function install()
{
    $error = array();

    //postback, create keys.php file
    $appName = $_POST['appName'];
    $teamNumber = $_POST['teamNumber'];
    $url = $_POST['url'];
    $mysqlHost = $_POST['mysqlHost'];
    $mysqlDbName = $_POST['mysqlDbName'];
    $mysqlUsername = $_POST['mysqlUsername'];
    $mysqlPassword = $_POST['mysqlPassword'];
    $blueAllianceApiKey = $_POST['blueAllianceApiKey'];
    $customApiKey = $_POST['customApiKey'];

    if (!file_put_contents('classes/Keys.php',
        "<?php
define('APP_NAME', '$appName');
define('TEAM_NUMBER', '$teamNumber');

define('URL_PATH', '$url');

define('MYSQL_HOST', '$mysqlHost');
define('MYSQL_DB', '$mysqlDbName');
define('MYSQL_USER', '$mysqlUsername');
define('MYSQL_PASSWORD', '$mysqlPassword');

define('BLUE_ALLIANCE_KEY', '$blueAllianceApiKey');
define('API_KEY', '$customApiKey');
?>")) {
        $error['error'] = true;
        $error['code'] = FILE_WRITE_FAIL_CODE;
        return $error;
    }

    //run the cron scripts to generate events and teams
    $url = $_SERVER['HTTP_HOST'] . '/cron/GetEvents.php';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    if (!curl_exec($ch)) {
        $error['error'] = true;
        $error['code'] = FILE_WRITE_FAIL_CODE;
        return $error;
    }

    $url = $_SERVER['HTTP_HOST'] . '/cron/GetTeams.php';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    if (!curl_exec($ch)) {
        $error['error'] = true;
        $error['code'] = FILE_WRITE_FAIL_CODE;
        return $error;
    }

    header('Location: ' . URL_PATH);

}



?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Install</title>

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
  <?php
  if(isPostBack())
  {
      if($error['error']) {
          echo
              "<script>
                $(document).ready(function()
                {
                    'use strict';
                    window['counter'] = 0;
                    var snackbarContainer = document.querySelector('#demo-toast-example');
                    var showToastButton = document.querySelector('#demo-show-toast');
            
                    'use strict';
                    var data = {message: 'Install failed. Code: " . $error['code'] . "'};
                         snackbarContainer.MaterialSnackbar.showSnackbar(data);
                     });
              </script>";
      }
  }
  ?>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
          <h3>Install</h3>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
          <div class="version">Version <?php echo VERSION ?></div>
      </header>
      <main class="mdl-layout__content">
          <div class="mdl-layout__tab-panel is-active" id="overview">
              <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                  <div class="mdl-card mdl-cell mdl-cell--12-col">
                      <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" style="padding-top: 30px;" id="scout-card-form">

                          <strong style="padding-left: 40px; padding-top: 10px;">App Settings</strong>
                          <div class="mdl-card__supporting-text">
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <input required class="mdl-textfield__input" type="text" name="appName">
                                  <label class="mdl-textfield__label" >App Name</label>
                              </div>

                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <input required class="mdl-textfield__input" type="text" name="teamNumber">
                                  <label class="mdl-textfield__label" >Team Number</label>
                              </div>
                          </div>

                          <strong style="padding-left: 40px;">Server Settings</strong>
                          <div class="mdl-card__supporting-text">
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <input required  class="mdl-textfield__input" type="text" placeholder="http(s)://subdomain.domain.com" name="url">
                                  <label class="mdl-textfield__label" >Full URL</label>
                              </div>
                          </div>

                          <strong style="padding-left: 40px; padding-top: 10px;">Database Settings</strong>
                          <div class="mdl-card__supporting-text">
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <input required  class="mdl-textfield__input" type="text" placeholder="127.0.0.1" name="mysqlHost">
                                  <label class="mdl-textfield__label" >MySQL Host</label>
                              </div>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <input required class="mdl-textfield__input" type="text" name="mysqlDbName">
                                  <label class="mdl-textfield__label" >MySQL Database Name</label>
                              </div>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <input required class="mdl-textfield__input" type="text" name="mysqlUsername">
                                  <label class="mdl-textfield__label" >MySQL Username</label>
                              </div>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <input required class="mdl-textfield__input" type="text" name="mysqlPassword">
                                  <label class="mdl-textfield__label" >MySQL Password</label>
                              </div>
                          </div>

                          <strong style="padding-left: 40px; padding-top: 10px;">API Keys</strong>
                          <div class="mdl-card__supporting-text">
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <input required class="mdl-textfield__input" type="text" name="blueAllianceApiKey">
                                  <label class="mdl-textfield__label" >Blue Alliance API Key</label>
                              </div>

                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <input required class="mdl-textfield__input" type="text" name="customApiKey">
                                  <label class="mdl-textfield__label" >Custom API Key</label>
                              </div>
                          </div>

                        <div class="mdl-card__supporting-text" style="margin-bottom: 30px;">
                            <button name="save" type="submit" class="mdl-button mdl-js-button mdl-button--raised">
                                  Save
                            </button>
                        </div>

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
  </body>
</html>
