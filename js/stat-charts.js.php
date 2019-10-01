<?php
require_once("../config.php");
require_once(ROOT_DIR . "/interfaces/AllianceColors.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Matches.php");

$eventId = $_GET['eventId'];
$matchId = $_GET['matchId'];

$event = Events::withId($eventId);

if(!empty($matchId))
    $match = Matches::withId($matchId);

$keys = ScoutCardInfoKeys::getKeys(null, $event);

$keyStates = array();

foreach ($keys as $key)
    if($key->IncludeInStats)
        $keyStates[$key->KeyState] = $key->KeyState;
?>

var <?php
    $varStr = '';
    foreach ($keyStates as $keyState => $placeholder)
    {
        $keyState = str_replace(' ', '', $keyState);
        if(!empty($varStr))
            $varStr .= ', ';

        $varStr .= $keyState . 'Chart';
    }

    echo $varStr . ';';
    ?>

var teamList =
    ($('#teamId').length === 0) ?
        []
    :
        $('#teamId').val().replace(/[^0-9,.]/g,'').split(',').filter(function (el)
        {
            return el != null && el != "";
        });

var matchId = '<?php echo ((empty($match)) ? '' : $match->Key) ?>';

var graphData;

$(document).ready(function ()
{
    downloadGraphData();
});

/**
* Gathers teams from chips and searches for results
 */
function searchTeams()
{
    //reset teamlist array
    teamList = []

    //gather all id's from the chips
    $($('#team-chips').children()).each(function(key, value)
    {
        teamList.push($(value).children(':first').html());
    });

    downloadGraphData();
}

/**
 * Downloads graph data from the ajax script
 */
function downloadGraphData()
{
    //get data from the ajax script
    $.post('<?php echo AJAX_URL ?>ajax.php',
        {
            action: 'load_stats',
            eventId: '<?php echo $event->BlueAllianceId ?>',
            teamIds: JSON.stringify(teamList),
            matchId: matchId,
            matchData: (teamList.length === 1)
        },
        function(data)
        {
            graphData = JSON.parse(data);

            updateGraphs();

            <?php
            foreach ($keyStates as $keyState => $placeholder)
            {
            $keyState = str_replace(' ', '', $keyState);
            ?>
            //set the change listener for the auto item
            $('#<?php echo 'change' . $keyState . 'Item' ?>').change(function()
            {
                generateData($(this).children('option:selected').val(), document.getElementById('<?php echo $keyState . 'Chart' ?>'));
            });
            <?php
            }
            ?>
        });
}

/**
 * Updates all the graphs on the page with the default records
 */
function updateGraphs()
{
    <?php
    foreach ($keyStates as $keyState => $placeholder)
    {
    ?>
    setItems('<?php echo json_encode(ScoutCardInfoKeys::getKeys(null, $event, $keyState)) ?>', document.getElementById('<?php echo str_replace(' ', '', $keyState) . 'Chart' ?>'), $('#<?php echo 'change' . str_replace(' ', '', $keyState) . 'Item' ?>'));
    <?php
    }
    ?>
}


/**
 * Sets all the items for the select boxes for each of the graphs
 * @param graphPeriod GRAPH_PERIODS that you would like to use for the items
 * @param context of the chart you want to use
 * @param selectBox you would want to use
 */
function setItems(graphPeriod, context, selectBox)
{
    graphPeriod = JSON.parse(graphPeriod);
    if(selectBox !== null)
        //empty out the contents of the select box before adding in the new contents
        $(selectBox).empty();

    var defaultVal;

    //for each item inside the GRAPH_PERIODS enum
    //match the specified graphperiod to the one in the enum
    $.each(graphPeriod , function(key, value)
    {
        if(value['IncludeInStats'] === 1)
        {
            //temp var to hold the default (first) value in the graph period


            if (defaultVal === undefined)
                defaultVal = value['KeyState'] + ' ' + value['KeyName'];

            if (selectBox !== null)
            //add options to the select boxes for the items within each graph item
                selectBox.append('<option value="' + value['KeyState'] + ' ' + value['KeyName'] + '">' + value['KeyName'] + '</option>');
        }
    });

    generateData(defaultVal, context);
}

/**
 * Generates the data from the ajax script and compiles it into the graph
 * @param graphItem this is an item from any of the enums specified in the GRAPH_PERIODS
 * @param context graph to populate
 */
function generateData(graphItem, context)
{
    //only 1 team specified, change the size of the graphs and hide the team breakdown
    if((teamList.length === 1) || matchId !== '')
    {
        $($(context).parent()[0]).removeClass('stats-chart').addClass('team-stats-chart');
        $('#oprDprStats').show();
    }
    else
    {
        $($(context).parent()[0]).removeClass('team-stats-chart').addClass('stats-chart');
        $('#oprDprStats').hide();
    }

    <?php
    foreach ($keyStates as $keyState => $placeholder)
    {
    $keyState = str_replace(' ', '', $keyState);
    ?>
    //if the chart context was the auto chart, update the auto chart
    if($(context).attr('id') === '<?php echo $keyState . 'Chart' ?>')
    {
        if(<?php echo $keyState . 'Chart' ?> !== undefined)
        <?php echo $keyState . 'Chart' ?>.destroy();
        <?php echo $keyState . 'Chart' ?> = createChart(context, graphData, graphItem);
    }
    <?php
    }
    ?>
}

/**
 * Creates a new Chart.js chart as a horizontal bar graph
 * @param context context of the canvas to add the chartbar to
 * @param jsonResponse to display for each team (Item Averages)
 * @param graphItem to display for each team (Item Averages)
 * @returns Chart
 */
function createChart(context, jsonResponse, graphItem)
{
    var labels = []; //labels AKA team numbers to show on the Y axis
    var graphData = []; //data AKA item averages to show on the graph
    var backgroundColors = []; //colors to indicate bad/warning/good stats
    var average = jsonResponse['EventAvg'][graphItem]; //get the event average
    var matchAveragData = []; //get the event average

    //match data specified
    if(jsonResponse['MatchAvgs'] !== undefined)
    {
        //for each item (team) inside the graph data, calculate and store the averages
        $.each(jsonResponse['MatchAvgs'], function(matchId, averages)
        {
            //dont add row for event avg
            if(matchId !== 'EventAvg')
            {
                var val = averages[graphItem]; //store the value of the teams averages for the specified item

                matchAveragData.push(val); //add the item average to the data
            }
        });
    }

    //for each item (team) inside the graph data, calculate and store the averages
    $.each(((matchAveragData.length > 0) ? jsonResponse[teamList[0]] : jsonResponse), function(key, averages)
    {
        //dont add row for event avg
        if(key !== 'EventAvg' && key !== 'MatchAvgs')
        {
            var val = averages[graphItem]; //store the value of the teams averages for the specified item

            labels.push(matchAveragData.length > 0 ? 'Quals ' + key : key); //key to the label
            graphData.push(val); //add the item average to the data

            //match not specified, assign colors based on score
            if(matchId === '')
            {
                //if the average is more than double the event average, that's a good (green) stat
                if (val > average * 1.20)
                    backgroundColors.push('#64FF62');

                //if the average is less than double but still greater to or equal to the event average, that's a warning (yellow) stat
                else if (val >= average * .8)
                    backgroundColors.push('#FFD966');

                // bad = less than 20% lower
                else
                    backgroundColors.push('#E67C73');
            }

            //match specified, assign colors based on alliance
            else
                backgroundColors.push(averages['Alliance Color'] === '<?php echo AllianceColors::RED ?>' ? '<?php echo AllianceColors::RED_RGBA ?>' : '<?php echo AllianceColors::BLUE_RGBA ?>');

        }
    });

    var xAxesTitle, yAxesTitle;

    if(matchAveragData.length > 0)
    {
        yAxesTitle = graphItem;
        xAxesTitle = 'Matches';
    }
    else
    {
        xAxesTitle = (matchId === '' ? 'Average ' : '') + graphItem;
        yAxesTitle = 'Teams';
    }

    xAxesTitle = xAxesTitle.toUpperCase();
    yAxesTitle = yAxesTitle.toUpperCase();

    var maxData = 0;
    var minData = 0;

    $(graphData).each(function(index, element)
    {
        if(element > maxData)
            maxData = element;
        else if(element < minData)
            minData = element;
    });

    var maxData2 = 0;
    var minData2 = 0;

    $(matchAveragData).each(function(index, element)
    {
        if(element > maxData2)
            maxData2 = element;
        else if(element < minData2)
            minData2 = element;
    });

    if(maxData2 > maxData)
        maxData = maxData2;

    if (average > maxData)
        maxData = average;

    if(minData2 < minData)
        minData = minData2;

    if (average < minData)
        minData = average;

    var lineGraph = matchAveragData.length > 0;

    return new Chart(context,
        {
            //graph type
            type: (lineGraph) ? 'line' : 'horizontalBar',
            data:(lineGraph) ?
                {
                    //match ids
                    labels: labels,
                    datasets:
                        [
                            {
                                label: 'Team Average',

                                //item averages
                                data: graphData,
                                cubicInterpolationMode: 'monotone',
                                backgroundColor: 'rgba(3, 169, 244, 0.2)',
                                borderColor: ['#03A9F4']
                            },
                            {
                                label: 'Match Average',

                                //item averages
                                data: matchAveragData,
                                cubicInterpolationMode: 'monotone',
                                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                                borderColor: ['#F00']
                            }
                        ]
                }
                :
                {
                    //team ids
                    labels: labels,
                    datasets:
                        [
                            {
                                //item averages
                                data: graphData,

                                //stat colors
                                backgroundColor: backgroundColors
                            }
                        ]
                },
            options: {
                legend:
                    {
                        //hide / show generated data name
                        display: lineGraph
                    },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem)
                        {
                            //tooltip title
                            return (lineGraph) ? ' ' + tooltipItem.yLabel : (matchId === '' ? 'Average ' : ' ') + tooltipItem.xLabel;
                        }
                    }
                },
                maintainAspectRatio: false,
                responsive: true,
                scales:
                {
                    yAxes: [{
                        ticks: (lineGraph) ?
                            {
                                beginAtZero: true,
                                max: (maxData === 0) ? 1 : maxData * 1.1,
                                min: ((minData >= 0) ? 0 : minData * 1.5)
                            }
                            :
                            {},
                        scaleLabel: {
                            display: true,
                            labelString: yAxesTitle
                        }
                    }],
                    xAxes: [{
                        ticks: (lineGraph) ?
                            {}
                            :
                            {
                                beginAtZero: true,
                                max: maxData * 1.05,
                                min: ((minData >= 0) ? 0 : minData * 1.1)
                            },
                        scaleLabel: {
                            display: true,
                            labelString: xAxesTitle
                        }
                    }]
                },
                annotation: {
                    //adds the event average vertical line
                    annotations: [{
                        type: 'line',
                        mode: (lineGraph) ? 'horizontal' : 'vertical',
                        scaleID: (lineGraph) ? 'y-axis-0' : 'x-axis-0',
                        value: average,
                        borderColor: '#0288D1',
                        borderWidth: 2,
                        borderDash: [7],
                        label: {
                            enabled: true,
                            content: 'Event Average ' + Math.round(average * 100.00) / 100.00
                        }
                    }]
                }
            }

        });
}
