<?php
require_once("../../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");

$yearId = $_GET['yearId'];
$year = Years::withId($coreDb, $yearId);

$team = Teams::withId($coreDb, getCoreAccount()->TeamId)

?>

<!doctype html>
<html lang="en">
  <head>
    <title><?php echo $year->toString() ?></title>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <?php
        $header = new Header($year->toString(), null, null, null, $year, ADMIN_URL . 'list?yearId=' . $year->Id);

        echo $header->toHtml();
        ?>
      <main class="mdl-layout__content">
          <?php

          foreach(Events::getObjects($coreDb, $year, $team) as $event)
              echo $event->toHtml();

          ?>

          <?php require_once(INCLUDES_DIR . 'footer.php') ?>
      </main>
    </div>
    <?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
  </body>
</html>
