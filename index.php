<?php
require_once("config.php");
require_once("classes/Events.php");

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Events</title>
    <?php require_once('includes/meta.php') ?>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <?php
        $header = new Header('Events', null, null, null);

        echo $header->toHtml();
        ?>
      <main class="mdl-layout__content">
          <?php

          foreach(Events::getObjects() as $event)
              echo $event->toHtml();

          ?>


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
  </body>
</html>
