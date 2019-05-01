<?php
require_once("config.php");
require_once("classes/Teams.php");
require_once("classes/Events.php");

$eventId = $_GET['eventId'];

$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo $event->Name; ?></title>
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Teams', '/team-list.php?eventId=' . $event->BlueAllianceId, true);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event->BlueAllianceId);

    echo $header->toString();
    ?>
    <main class="mdl-layout__content">

        <?php

        foreach (Teams::getTeamsAtEvent($eventId) as $team)
        {
            ?>
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp team-card">
                    <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--white mdl-color-text--white">

        <?php
            $robotMediaUri = Teams::getProfileImageUri($team['Id']);

            if(!empty($robotMediaUri))
            {
                $robotMediaUri = ROBOT_MEDIA_URL . $robotMediaUri;

                    ?>
                        <div style="height: unset" >
                          <div class="team-card-image" style="background-image: url('<?php echo $robotMediaUri ?>')">

                          </div>
                        </div>
        <?php
            }

            else
            {

                ?>
                        <div style="height: unset" >
                          <div class="team-card-image" style="background-image: url(http://scouting.wiredcats5885.ca/assets/robot-media/frc_logo.jpg)">

                          </div>
                        </div>
        <?php
            }
            ?>
                    </header>
                    <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
                        <div class="mdl-card__supporting-text">
                            <h4><?php echo $team['Id'] . ' - ' . $team['Name']?></h4>
                        <?php echo $team['City'] . ', ' . $team['StateProvince'] . ', ' . $team['Country']?>
                    </div>
                        <div class="mdl-card__actions">
                            <a href="/team-matches.php?eventId=<?php echo $eventId ?>&teamId=<?php echo $team['Id']?>" class="mdl-button">View</a>
                        </div>
                    </div>
                </section>
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
