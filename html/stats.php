<?php
require_once("config.php");
require_once("classes/Teams.php");
require_once("classes/Events.php");

$eventId = $_GET['eventId'];

$event = new Events();
$event->load($eventId);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Stats</title>

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="shortcut icon" href="images/favicon.png">

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.deep_purple-pink.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>



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

        .container{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}@media (min-width:576px){.container{max-width:540px}}@media (min-width:768px){.container{max-width:720px}}@media (min-width:992px){.container{max-width:960px}}@media (min-width:1200px){.container{max-width:1140px}}
        .row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}
        .col,.col-1,.col-10,.col-11,.col-12,.col-2,.col-3,.col-4,.col-5,.col-6,.col-7,.col-8,.col-9,.col-auto,.col-lg,.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-lg-auto,.col-md,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-auto,.col-sm,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-sm-auto,.col-xl,.col-xl-1,.col-xl-10,.col-xl-11,.col-xl-12,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9,.col-xl-auto{position:relative;width:100%;padding-right:15px;padding-left:15px}.col{-ms-flex-preferred-size:0;flex-basis:0;-ms-flex-positive:1;flex-grow:1;max-width:100%}
    </style>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            <h3><?php echo $event->Name; ?></h3>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
            <a href="/" class="mdl-layout__tab">Events</a>
            <a href="/teams.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab">Teams</a>
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
            <a href="/stats.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab is-active">Stats</a>
            <a href="/match-overview.php?eventId=<?php echo $event->BlueAllianceId; ?>" class="mdl-layout__tab ">Match Overview</a>
        </div>
    </header>
    <main class="mdl-layout__content">
        <div style="padding: 1em 5em 0 5em;">

            <div class="container" style="width: 500px; margin-left: unset; padding-left: unset;">
                <div class="row">
                    <div class="col">
                        <div style="width: 100px; height: 10px; background-color: #FFD966;"></div>
                        AUTONOMOUS
                    </div>
                    <div class="col">
                        <div style="width: 100px; height: 10px; background-color: #00FFFF;"></div>
                        TELEOP
                    </div>
                    <div class="col">
                        <div style="width: 100px; height: 10px; background-color: #9400ff;"></div>
                        END GAME
                    </div>
                </div>
            </div>


        </div>
        <table id="stats_table" class="display stats-table">
            <thead>
            <tr>
                <th>Team Number</th>
                <th>MAX/AVG</th>
                <th style="background-color: #FFD966;">Exit Habitat</th>
                <th style="background-color: #FFD966;">Hatch Panels</th>
                <th style="background-color: #FFD966;">Cargo</th>
                <th style="background-color: #00FFFF;">Hatch Panels</th>
                <th style="background-color: #00FFFF;">Cargo</th>
                <th style="background-color: #00FFFF;">Rockets Complete</th>
                <th style="background-color: #9400ff; color: white;">Returned To Habitat</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

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

<script>

    $(document).ready( function () {
        $('#stats_table').DataTable({
            "paging": false,
            "ajax": {
                "url": "/ajax/ajax.php",
                "type": "POST",
                "data": {
                    action: 'load_stats',
                    eventId: '<?php echo $event->BlueAllianceId; ?>'
                },
                "error": function (reason) {

                }

            },
            'fnRowCallback': function( nRow, aData, iDisplayIndex ) {
                

                $(nRow).each(function()
                {
                    var i = 0;

                   $(this).children('td').each(function()
                   {
                       switch(i)
                       {
                           case 1:
                               if($(this).html() === 'MAX')
                                   $(this).css('background-color', '#64FF62');

                               else if($(this).html() === 'AVG')
                                   $(this).css('background-color', '#9FC5E8');

                               else
                                   $(this).css('background-color', '#ffa74f');

                               break;

                           case 2:
                               if($(this).html() > 0)
                                   $(this).css('background-color', '#64FF62');

                               else
                                   $(this).css('background-color', '#E67C73');

                               break;

                           case 3:
                               if($(this).html() > 0)
                                   $(this).css('background-color', '#64FF62');

                               else
                                   $(this).css('background-color', '#E67C73');

                               break;

                           case 4:
                               if($(this).html() > 0)
                                   $(this).css('background-color', '#64FF62');

                               else
                                   $(this).css('background-color', '#E67C73');

                               break;

                           case 5:
                               if($(this).html() > 0)
                                   $(this).css('background-color', '#64FF62');

                               else
                                   $(this).css('background-color', '#E67C73');

                               break;

                           case 6:

                               if($(this).html() > 0)
                                   $(this).css('background-color', '#64FF62');

                               else
                                   $(this).css('background-color', '#E67C73');

                               break;

                           case 7:
                               if($(this).html() > 0)
                                   $(this).css('background-color', '#64FF62');

                               else
                                   $(this).css('background-color', '#E67C73');

                               break;

                           case 8:
                               if($(this).html() !== "No")
                                   $(this).css('background-color', '#64FF62');

                               else
                                   $(this).css('background-color', '#E67C73');

                               break;
                       }

                       i++;
                   });
                });

            },
        });
    });



</script>
</body>
</html>
