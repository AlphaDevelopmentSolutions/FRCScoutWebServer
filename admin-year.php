<?php
require_once("config.php");

if(loggedIn())
    header('Location: '. URL_PATH);

require_once(ROOT_DIR . "/classes/tables/Years.php");
require_once(ROOT_DIR . "/classes/tables/RobotInfoKeys.php");

$yearId = $_GET['yearId'];
$year = Years::withId($yearId);

?>

<!doctype html>
<html lang="en">
  <head>
    <title><?php echo $year->toString() ?></title>
    <?php require_once('includes/meta.php') ?>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <?php
        $header = new Header($year->toString() . ' - Admin Panel', null, null, null, $year);

        echo $header->toHtml();
        ?>
      <main class="mdl-layout__content">

          <table align="center" class="mdl-data-table mdl-js-data-table mdl-shadow--2dp admin-table">
              <thead>
              <tr>
                  <th>Year</th>
                  <th>State</th>
                  <th>Name</th>
                  <th>Sort Order</th>
              </tr>
              </thead>
              <tbody>

              <?php

          $robotInfoKeys = RobotInfoKeys::getObjects();

          foreach ($robotInfoKeys as $robotInfoKey)
          {
              ?>
                  <tr>
                      <td><?php echo $robotInfoKey->YearId ?></td>
                      <td><?php echo $robotInfoKey->KeyState ?></td>
                      <td><?php echo $robotInfoKey->KeyName ?></td>
                      <td><?php echo $robotInfoKey->SortOrder ?></td>
                  </tr>
          <?php
          }

          ?>
              </tbody>
          </table>


      </main>
    </div>
    <?php require_once('includes/bottom-scripts.php') ?>
  </body>
</html>
