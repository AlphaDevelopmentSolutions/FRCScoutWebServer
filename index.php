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

          foreach(Events::getEvents() as $event)
          {
            ?>
                <div class="mdl-layout__tab-panel is-active" id="overview">
                  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                      <div class="mdl-card__supporting-text">
                        <h4><?php echo $event['Name'] ?></h4>
                        <?php echo $event['City'] . ', ' . $event['StateProvince'] . ', ' . $event['Country'] ?><br><br>
                        <?php echo date('F j', strtotime($event['StartDate'])) . ' to ' . date('F j', strtotime($event['EndDate'])) ?>
                      </div>
                      <div class="mdl-card__actions">
                        <a href="/match-list.php?eventId=<?php echo $event['BlueAllianceId'] ?>" class="mdl-button">View</a>
                      </div>
                    </div>
                  </section>
                </div>
        <?php
          }

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
