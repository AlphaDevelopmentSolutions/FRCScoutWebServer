<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Years</title>
    <?php require_once('includes/meta.php') ?>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <?php
        $header = new Header('Years');

        echo $header->toHtml();
        ?>
      <main class="mdl-layout__content">
          <?php

          foreach(Years::getObjects() as $year)
              echo $year->toHtml();

          ?>

          <?php require_once('includes/footer.php') ?>
      </main>
    </div>
    <?php require_once('includes/bottom-scripts.php') ?>
  </body>
</html>
