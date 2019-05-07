<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/Teams.php");
require_once(ROOT_DIR . "/classes/Events.php");

$eventId = $_GET['eventId'];

$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>

    <title>Stats</title>
    <?php require_once('includes/meta.php') ?>

    <script src="<?php echo URL_PATH ?>/js/Chart.min.js"></script>
    <link rel="stylesheet" href="<?php echo URL_PATH ?>/css/Chart.min.css">
    <script src="<?php echo URL_PATH ?>/js/chartjs-plugin-annotation.min.js"></script>

</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Stats', '/stats.php?eventId=' . $event->BlueAllianceId, true);
    $navBarLinksArray[] = new NavBarLink('Legacy Stats', '/stats-legacy.php?eventId=' . $event->BlueAllianceId);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event->BlueAllianceId);

    echo $header->toHtml();
    ?>


    <main class="mdl-layout__content">
        <div class="content-grid mdl-grid">

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input" id="changeAutoItem" name="changeAutoItem" onchange="generateData($(this).children('option:selected').val(), document.getElementById('autoChart'))">
                    </select>
                    <label class="mdl-textfield__label" for="changeAutoItem">Item</label>
                </div>
                <div style="height: 750px;">
                    <canvas id="autoChart"></canvas>
                </div>
            </div>

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input" id="changeTeleopItem" name="changeTeleopItem" onchange="generateData($(this).children('option:selected').val(), document.getElementById('teleopChart'))">
                    </select>
                    <label class="mdl-textfield__label" for="changeTeleopItem">Item</label>
                </div>
                <div style="height: 750px;">
                    <canvas id="teleopChart"></canvas>
                </div>
            </div>

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input" id="changeEndGameItem" name="changeEndGameItem" onchange="generateData($(this).children('option:selected').val(), document.getElementById('endGameChart'))">
                    </select>
                    <label class="mdl-textfield__label" for="changeEndGameItem">Item</label>
                </div>
                <div style="height: 750px;">
                    <canvas id="endGameChart"></canvas>
                </div>
            </div>

            <div class="mdl-cell stats-cell">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                    <select class="mdl-textfield__input" id="changePostGameItem" name="changePostGameItem" onchange="generateData($(this).children('option:selected').val(), document.getElementById('postGameChart'))">
                    </select>
                    <label class="mdl-textfield__label" for="changePostGameItem">Item</label>
                </div>
                <div style="height: 750px;">
                    <canvas id="postGameChart"></canvas>
                </div>
            </div>
        </div>
    </main>

</div>
<?php require_once('includes/bottom-scripts.php') ?>
<script>

    var autoChart, teleopChart, endGameChart, postGameChart;

    const AUTO_ITEMS =
        {
            Hatches : 'AutonomousHatchPanelsSecured',
            Cargo : 'AutonomousCargoStored'
        };

    const TELEOP_ITEMS =
        {
            Hatches : 'TeleopHatchPanelsSecured',
            Cargo : 'TeleopCargoStored'
        };

    const END_GAME_ITEMS =
        {
            Return_To_Hab: 'EndGameReturnedToHabitat'
        };

    const POST_GAME_ITEMS =
        {
            Defense_Rating: 'DefenseRating',
            Offense_Rating: 'OffenseRating',
            Drive_Rating: 'DriveRating'
        };

    const GRAPH_PERIODS =
        {
            Autonomous: AUTO_ITEMS,
            Teleop: TELEOP_ITEMS,
            EndGame: END_GAME_ITEMS,
            PostGame: POST_GAME_ITEMS
        };

    $(document).ready(function ()
    {
        setItems(GRAPH_PERIODS.Autonomous, document.getElementById('autoChart'), $('#changeAutoItem'));
        setItems(GRAPH_PERIODS.Teleop, document.getElementById('teleopChart'), $('#changeTeleopItem'));
        setItems(GRAPH_PERIODS.EndGame, document.getElementById('endGameChart'), $('#changeEndGameItem'));
        setItems(GRAPH_PERIODS.PostGame, document.getElementById('postGameChart'), $('#changePostGameItem'));
    });


    /**
     * Sets all the items for the select boxes for each of the graphs
     * @param graphPeriod GRAPH_PERIODS that you would like to use for the items
     * @param context of the chart you want to use
     * @param selectBox you would want to use
     */
    function setItems(graphPeriod, context, selectBox)
    {
        //for each item inside the GRAPH_PERIODS enum
        //match the specified graphperiod to the one in the enum
        $.each(GRAPH_PERIODS , function(key, value)
        {
            if(key === graphPeriod || value === graphPeriod)
            {
                //temp var to hold the default (first) value in the graph period
                var defaultVal;

                //iterate through to set the default val
                //if default val is set, break out of the loop
                $.each(value, function (key, value)
                {
                    if(defaultVal === undefined)
                        defaultVal = value;
                    else
                        return;
                });

                //get the ball rolling for the ajax script by generating the graphs
                generateData(defaultVal, context);

                //add options to the select boxes for the items within each graph item
                $.each(value , function(key, value)
                {
                    selectBox.append('<option value="' + value + '">' + key + '</option>');
                });
            }
        });
    }

    /**
     * Generates the data from the ajax script and compiles it into the graph
     * @param graphItem this is an item from any of the enums specified in the GRAPH_PERIODS
     * @param context graph to populate
     */
    function generateData(graphItem, context)
    {
        //get data from the ajax script
        $.post('/ajax/ajax.php',
            {
                action: 'load_stats',
                eventId:'<?php echo $event->BlueAllianceId; ?>'
            },
            function(data)
            {
                //parse the response data into JSON
                var jsonResponse = JSON.parse(data);

                var labels = []; //labels AKA team numbers to show on the Y axis
                var data = []; //data AKA item averages to show on the graph
                var backgroundColors = []; //colors to indicate bad/warning/good stats
                var average = jsonResponse['EventAvg'][graphItem]; //get the event average

                //for each item (team) inside the graph data, calculate and store the averages
                $.each(jsonResponse, function(teamId, averages)
                {
                    //dont add row for event avg
                    if(teamId !== 'EventAvg')
                    {
                        var val = averages[graphItem]; //store the value of the teams averages for the specified item

                        labels.push(teamId); //add the team id to the label
                        data.push(val); //add the item average to the data

                        //if the average is more than double the event average, that's a good (green) stat
                        if(val > average * 1.45)
                            backgroundColors.push('#64FF62');

                        //if the average is less than double but still greater to or equal to the event average, that's a warning (yellow) stat
                        else if (val >= average)
                            backgroundColors.push('#FFD966');

                        //if the average is less than the event average, that's a bad (red) stat
                        else
                            backgroundColors.push('#E67C73');
                    }
                });

                //if the chart context was the auto chart, update the auto chart
                if($(context).attr('id') === 'autoChart')
                {
                    if(autoChart !== undefined)
                        autoChart.destroy();
                    autoChart = createChart(context, labels, data, backgroundColors, average, 'Autonomous');
                }

                //if the chart context was the teleop chart, update the teleop chart
                else if($(context).attr('id') === 'teleopChart')
                {
                    if(teleopChart !== undefined)
                        teleopChart.destroy();
                    teleopChart = createChart(context, labels, data, backgroundColors, average, 'Teleop');
                }

                //if the chart context was the end game chart, update the end game chart
                else if($(context).attr('id') === 'endGameChart')
                {
                    if(endGameChart !== undefined)
                        endGameChart.destroy();
                    endGameChart = createChart(context, labels, data, backgroundColors, average, 'End Game');
                }

                //if the chart context was the post game chart, update the post game chart
                else if($(context).attr('id') === 'postGameChart')
                {
                    if(postGameChart !== undefined)
                        postGameChart.destroy();
                    postGameChart = createChart(context, labels, data, backgroundColors, average, 'Post Game');
                }
            });
    }

    /**
     * Creates a new Chart.js chart as a horizontal bar graph
     * @param context context of the canvas to add the chartbar to
     * @param labels all Y axis labels (Team Ids)
     * @param data to display for each team (Item Averages)
     * @param backgroundColors of the item average
     * @param average event average
     * @param title of the graph
     * @returns Chart
     */
    function createChart(context, labels, data, backgroundColors, average, title)
    {
        return new Chart(context,
            {
                //graph type
                type: 'horizontalBar',
                data:
                    {
                        //team ids
                        labels: labels,
                        datasets: [{
                            //item averages
                            data: data,

                            //stat colors
                            backgroundColor: backgroundColors
                        }]
                    },
                options: {
                    title:
                        {
                            //show graph title
                            display: true,
                            text: title
                        },
                    legend:
                        {
                            //hide legend generated by data name
                            display: false
                        },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem)
                            {
                                //tooltip title
                                return 'Average ' + tooltipItem.xLabel;
                            }
                        }
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    annotation: {
                        //adds the event average vertical line
                        annotations: [{
                            type: 'line',
                            mode: 'vertical',
                            scaleID: 'x-axis-0',
                            value: average,
                            borderColor: 'rgb(75, 192, 192)',
                            borderWidth: 4,
                            label: {
                                enabled: true,
                                content: 'Event Average ' + Math.round(average * 100.00) / 100.00
                            }
                        }]
                    }
                }

            });
    }
</script>
</body>
</html>
