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
    <title><?php echo $event->Name; ?></title>

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
                <th>Returned To Habitat</th>
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

                // <div style="background-color: ' . (($maxAvg == 'MAX') ? '#64FF62' : '#9FC5E8') . '">' . $maxAvg . '</div>';


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

                               else
                                   $(this).css('background-color', '#9FC5E8');

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
