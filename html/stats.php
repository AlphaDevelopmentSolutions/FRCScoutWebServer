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
    <meta name="google" content="notranslate">

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>



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

        .bs-tooltip-top, .bs-tooltip-auto[x-placement^="top"] {
            padding: 0.4rem 0;
        }

        .bs-tooltip-top .arrow, .bs-tooltip-auto[x-placement^="top"] .arrow {
            bottom: 0;
        }

        .bs-tooltip-top .arrow::before, .bs-tooltip-auto[x-placement^="top"] .arrow::before {
            top: 0;
            border-width: 0.4rem 0.4rem 0;
            border-top-color: #000;
        }

        .bs-tooltip-right, .bs-tooltip-auto[x-placement^="right"] {
            padding: 0 0.4rem;
        }

        .bs-tooltip-right .arrow, .bs-tooltip-auto[x-placement^="right"] .arrow {
            left: 0;
            width: 0.4rem;
            height: 0.8rem;
        }

        .bs-tooltip-right .arrow::before, .bs-tooltip-auto[x-placement^="right"] .arrow::before {
            right: 0;
            border-width: 0.4rem 0.4rem 0.4rem 0;
            border-right-color: #000;
        }

        .bs-tooltip-bottom, .bs-tooltip-auto[x-placement^="bottom"] {
            padding: 0.4rem 0;
        }

        .bs-tooltip-bottom .arrow, .bs-tooltip-auto[x-placement^="bottom"] .arrow {
            top: 0;
        }

        .bs-tooltip-bottom .arrow::before, .bs-tooltip-auto[x-placement^="bottom"] .arrow::before {
            bottom: 0;
            border-width: 0 0.4rem 0.4rem;
            border-bottom-color: #000;
        }

        .bs-tooltip-left, .bs-tooltip-auto[x-placement^="left"] {
            padding: 0 0.4rem;
        }

        .bs-tooltip-left .arrow, .bs-tooltip-auto[x-placement^="left"] .arrow {
            right: 0;
            width: 0.4rem;
            height: 0.8rem;
        }

        .bs-tooltip-left .arrow::before, .bs-tooltip-auto[x-placement^="left"] .arrow::before {
            left: 0;
            border-width: 0.4rem 0 0.4rem 0.4rem;
            border-left-color: #000;
        }

        .tooltip-inner {
            max-width: 200px;
            padding: 0.25rem 0.5rem;
            color: #fff;
            text-align: center;
            background-color: #000;
            border-radius: 0.25rem;
        }

        .tooltip {
            position: absolute;
            z-index: 1070;
            display: block;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-style: normal;
            font-weight: 400;
            line-height: 1.5;
            text-align: left;
            text-align: start;
            text-decoration: none;
            text-shadow: none;
            text-transform: none;
            letter-spacing: normal;
            word-break: normal;
            word-spacing: normal;
            white-space: normal;
            line-break: auto;
            font-size: 0.875rem;
            word-wrap: break-word;
            opacity: 0;
        }

        .tooltip.show {
            opacity: 0.9;
        }

        .tooltip .arrow {
            position: absolute;
            display: block;
            width: 0.8rem;
            height: 0.4rem;
        }

        .tooltip .arrow::before {
            position: absolute;
            content: "";
            border-color: transparent;
            border-style: solid;
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
            <?php include_once('includes/login-form.php') ?>
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
                    <div class="col" onclick="toggleAuto($(this));">
                        <div style="width: 100px; height: 10px; background-color: #FFD966;"></div>
                        <p>AUTONOMOUS</p>
                    </div>
                    <div class="col" onclick="toggleTeleop($(this));">
                        <div style="width: 100px; height: 10px; background-color: #00FFFF;"></div>
                        <p>TELEOP</p>
                    </div>
                    <div class="col" onclick="toggleEndGame($(this));">
                        <div style="width: 100px; height: 10px; background-color: #9400ff;"></div>
                        <p>END GAME</p>
                    </div>
                </div>
            </div>
            <div class="container" style="width: 500px; margin-left: unset; padding-left: unset;">
                <div class="row">
                    <div class="col" onclick="toggleMin($(this));">
                        <div style="width: 100px; height: 10px; background-color: #ffa74f;"></div>
                        <p>MIN</p>
                    </div>
                    <div class="col" onclick="toggleAvg($(this));">
                        <div style="width: 100px; height: 10px; background-color: #9FC5E8;"></div>
                        <p>AVG</p>
                    </div>
                    <div class="col" onclick="toggleMax($(this));">
                        <div style="width: 100px; height: 10px; background-color: #64FF62;"></div>
                        <p>MAX</p>
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
                <th style="background-color: #FFD966;">Hatch Panels Failed Attempts</th>
                <th style="background-color: #FFD966;">Cargo</th>
                <th style="background-color: #FFD966;">Cargo Failed  Attempts</th>
                <th style="background-color: #00FFFF;">Hatch Panels</th>
                <th style="background-color: #00FFFF;">Hatch Panels Failed  Attempts</th>
                <th style="background-color: #00FFFF;">Cargo</th>
                <th style="background-color: #00FFFF;">Cargo Failed  Attempts</th>
                <th style="background-color: #00FFFF;">Rockets Complete</th>
                <th style="background-color: #9400ff; color: white;">Returned To Habitat</th>
                <th style="background-color: #9400ff; color: white;">Returned To Habitat Failed  Attempts</th>
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

    var statsTable;

    var removeAuto = false,
        removeTeleop = false,
        removeEndGame = false;

    var removeMin = false,
        removeAvg = false,
        removeMax = false;

    var columnDefs = {
        "targets": [ 2 ],
        "visible": false,
        "searchable": false};

    $(document).ready( function () {

        statsTable = $('#stats_table').DataTable({
            "paging": false,
            "autoWidth": false,
            "ajax": {
                "url": "/ajax/ajax.php",
                "type": "POST",
                "data": function(d){
                    d.removeMin = removeMin;
                    d.removeAvg = removeAvg;
                    d.removeMax = removeMax;
                    d.action = 'load_stats';
                    d.eventId = '<?php echo $event->BlueAllianceId; ?>';
                },
                "error": function (reason) {

                }

            },
            'columnDefs': [function(d)
            {
                d.columnDefs = columnDefs;
            }],
            'drawCallback': function(settings)
            {
                $('[data-toggle="tooltip"]').tooltip();
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
                               calculateRowColor($(this));
                               break;

                           case (removeAuto ? -1 : 2):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'autoExitHabitatMinMatchIds', 'autoExitHabitatMaxMatchIds', $(this));
                               break;

                           case (removeAuto ? -2 : 3):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'autoHatchPanelsMinMatchIds', 'autoHatchPanelsMaxMatchIds', $(this));
                               break;

                           case (removeAuto ? -3 : 4):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'autoHatchPanelsAttemptsMinMatchIds', 'autoHatchPanelsAttemptsMaxMatchIds', $(this));
                               break;

                           case (removeAuto ? -4 : 5):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'autoCargoStoredMinMatchIds', 'autoCargoStoredMaxMatchIds', $(this));
                               break;

                           case (removeAuto ? -5 : 6):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'autoCargoStoredAttemptsMinMatchIds', 'autoCargoStoredAttemptsMaxMatchIds', $(this));
                               break;

                           case (removeTeleop ? -6 : removeAuto ? 2 : 7):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'teleopHatchPanelsMinMatchIds', 'teleopHatchPanelsMaxMatchIds', $(this));
                               break;

                           case (removeTeleop ? -7 : removeAuto ? 3 : 8):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'teleopHatchPanelsAttemptsMinMatchIds', 'teleopHatchPanelsAttemptsMaxMatchIds', $(this));
                               break;

                           case (removeTeleop ? -8 : removeAuto ? 4 : 9):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'teleopCargoStoredMinMatchIds', 'teleopCargoStoredMaxMatchIds', $(this));
                               break;

                           case (removeTeleop ? -9 : removeAuto ? 5 : 10):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'teleopCargoStoredAttemptsMinMatchIds', 'teleopCargoStoredAttemptsMaxMatchIds', $(this));
                               break;

                           case (removeTeleop ? -10 : removeAuto ? 6 : 11):
                               calculateRowColor($(this));
                               generateToolTips(aData, 'teleopRocketsCompleteMinMatchIds', 'teleopRocketsCompleteMaxMatchIds', $(this));
                               break;

                           case (removeAuto ? (removeTeleop ? 2 : 7) : removeTeleop ? 7 : 12):
                               if($(this).html() !== "No")
                                   $(this).css('background-color', '#64FF62');

                               else
                                   $(this).css('background-color', '#E67C73');
                               generateToolTips(aData, 'endGameReturnedToHabitatMinMatchIds', 'endGameReturnedToHabitatMaxMatchIds', $(this));

                               break;

                           case (removeAuto ? (removeTeleop ? 3 : 8) : removeTeleop ? 8 : 13):
                               if($(this).html() !== "No")
                                   $(this).css('background-color', '#64FF62');

                               else
                                   $(this).css('background-color', '#E67C73');
                               generateToolTips(aData, 'endGameReturnedToHabitatMinMatchIds', 'endGameReturnedToHabitatMaxMatchIds', $(this));

                               break;
                       }

                       i++;
                   });
                });

            },
        });

    });

    function toggleMin(element)
    {
        toggleCrossOut(element);

        removeMin = !removeMin;
        statsTable.ajax.reload();
    }

    function toggleMax(element)
    {
        toggleCrossOut(element);

        removeMax = !removeMax;
        statsTable.ajax.reload();
    }

    function toggleAvg(element)
    {
        toggleCrossOut(element);

        removeAvg = !removeAvg;
        statsTable.ajax.reload();
    }


    function toggleAuto(element)
    {
        toggleCrossOut(element);

        statsTable.columns(2).visible(removeAuto);
        statsTable.columns(3).visible(removeAuto);
        statsTable.columns(4).visible(removeAuto);
        statsTable.columns(5).visible(removeAuto);
        statsTable.columns(6).visible(removeAuto);

        removeAuto = !removeAuto;

        statsTable.ajax.reload();
    }

    function toggleTeleop(element)
    {
        toggleCrossOut(element);

        statsTable.columns(7).visible(removeTeleop);
        statsTable.columns(8).visible(removeTeleop);
        statsTable.columns(9).visible(removeTeleop);
        statsTable.columns(10).visible(removeTeleop);
        statsTable.columns(11).visible(removeTeleop);

        removeTeleop = !removeTeleop;

        statsTable.ajax.reload();
    }

    function toggleEndGame(element)
    {

        toggleCrossOut(element);

        statsTable.columns(12).visible(removeEndGame);
        statsTable.columns(13).visible(removeEndGame);

        removeEndGame = !removeEndGame;

        statsTable.ajax.reload();
    }

    function toggleCrossOut(element)
    {
        if($(element).css('text-decoration').indexOf('line-through') >= 0)
            $(element).css('text-decoration', 'unset');
        else
            $(element).css('text-decoration', 'line-through');
    }

    function calculateRowColor(element)
    {
        if($(element).html() > 0)
            $(element).css('background-color', '#64FF62');

        else
            $(element).css('background-color', '#E67C73');

        if($(element).html() === 'MAX')
            $(element).css('background-color', '#64FF62');

        else if($(element).html() === 'AVG')
            $(element).css('background-color', '#9FC5E8');

        else if($(element).html() === 'MIN')
            $(element).css('background-color', '#ffa74f');
    }

    function generateToolTips(aData, minMatchIdsKey, maxMatchIdsKey, element)
    {
        var tooltipText = '';

        if(aData[minMatchIdsKey] !== undefined)
        {
            aData[minMatchIdsKey].forEach(function (matchId) {
                tooltipText += 'Match ' + matchId + '<br>';
            });

            $(element).attr('data-toggle', 'tooltip').attr('data-html', 'true').attr('data-placement', 'top').attr('title', tooltipText);
        }
        else if(aData[maxMatchIdsKey] !== undefined)
        {
            aData[maxMatchIdsKey].forEach(function (matchId) {
                tooltipText += 'Match ' + matchId + '<br>';
            });

            $(element).attr('data-toggle', 'tooltip').attr('data-html', 'true').attr('data-placement', 'top').attr('title', tooltipText);
        }
    }



</script>
</body>
</html>
