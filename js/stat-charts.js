
var autoChart, teleopChart, endGameChart, postGameChart, dodBreakdownChart;

var teamList =
    ($('#teamId').length === 0) ?
        []
    :
        $('#teamId').val().replace(/[^0-9,.]/g,'').split(',').filter(function (el)
        {
            return el != null && el != "";
        });

var matchId = ($('#matchId').length === 0) ? '' : $('#matchId').val();

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
    updateGraphs();

    //set the change listener for the auto item
    $('#changeAutoItem').change(function()
    {
        generateData($(this).children('option:selected').val(), document.getElementById('autoChart'))
    });

    //set the change listener for the teleop item
    $('#changeTeleopItem').change(function()
    {
        generateData($(this).children('option:selected').val(), document.getElementById('teleopChart'))
    });

    //set the change listener for the end game item
    $('#changeEndGameItem').change(function()
    {
        generateData($(this).children('option:selected').val(), document.getElementById('endGameChart'))
    });

    //set the change listener for the post game item
    $('#changePostGameItem').change(function()
    {
        generateData($(this).children('option:selected').val(), document.getElementById('postGameChart'))
    });


    //set the change listener for the search field
    $('#teamSearch').change(function()
    {
        //filter the string and remove empty records
        teamList = $(this).val().replace(/[^0-9,.]/g,'').split(',').filter(function (el)
        {
            return el != null && el != "";
        });

        updateGraphs();
    });
});

/**
 * Updates all the graphs on the page with the default records
 */
function updateGraphs()
{
    setItems(GRAPH_PERIODS.Autonomous, document.getElementById('autoChart'), $('#changeAutoItem'));
    setItems(GRAPH_PERIODS.Teleop, document.getElementById('teleopChart'), $('#changeTeleopItem'));
    setItems(GRAPH_PERIODS.EndGame, document.getElementById('endGameChart'), $('#changeEndGameItem'));
    setItems(GRAPH_PERIODS.PostGame, document.getElementById('postGameChart'), $('#changePostGameItem'));

    if(teamList.length === 1 || matchId !== '')
        setItems(GRAPH_PERIODS.EndGame, document.getElementById('dodBreakdownChart'), null);
}


/**
 * Sets all the items for the select boxes for each of the graphs
 * @param graphPeriod GRAPH_PERIODS that you would like to use for the items
 * @param context of the chart you want to use
 * @param selectBox you would want to use
 */
function setItems(graphPeriod, context, selectBox)
{
    if(selectBox !== null)
        //empty out the contents of the select box before adding in the new contents
        $(selectBox).empty();

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


                generateData(defaultVal, context);

            if(selectBox !== null)
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
    var matchData = (teamList.length === 1) && ($(context).attr('id') !== 'dodBreakdownChart');

    //get data from the ajax script
    $.post('/ajax/ajax.php',
        {
            action: 'load_stats',
            eventId: $('#eventId').val(),
            teamIds: JSON.stringify(teamList),
            matchId: matchId,
            matchData: matchData
        },
        function(data)
        {
            //only 1 team specified, change the size of the graphs and hide the team breakdown
            if((teamList.length === 1) || matchId !== '')
            {
                $($(context).parent()[0]).removeClass('stats-chart').addClass('team-stats-chart');
                $('#dodBreakdownChart').show();
            }
            else
            {
                $('#dodBreakdownChart').hide();
                $($(context).parent()[0]).removeClass('team-stats-chart').addClass('stats-chart');
            }

            //parse the response data into JSON
            var jsonResponse = JSON.parse(data);

            //if the chart context was the auto chart, update the auto chart
            if($(context).attr('id') === 'autoChart')
            {
                if(autoChart !== undefined)
                    autoChart.destroy();
                autoChart = createChart(context, jsonResponse, graphItem, 'Autonomous');
            }

            //if the chart context was the teleop chart, update the teleop chart
            else if($(context).attr('id') === 'teleopChart')
            {
                if(teleopChart !== undefined)
                    teleopChart.destroy();
                teleopChart = createChart(context, jsonResponse, graphItem, 'Teleop');
            }

            //if the chart context was the end game chart, update the end game chart
            else if($(context).attr('id') === 'endGameChart')
            {
                if(endGameChart !== undefined)
                    endGameChart.destroy();
                endGameChart = createChart(context, jsonResponse, graphItem, 'End Game');
            }

            //if the chart context was the post game chart, update the post game chart
            else if($(context).attr('id') === 'postGameChart')
            {
                if(postGameChart !== undefined)
                    postGameChart.destroy();
                postGameChart = createChart(context, jsonResponse, graphItem, 'Post Game');
            }

            //if the chart context was the dod breakdown chart, update the dod breakdown chart
            if($(context).attr('id') === 'dodBreakdownChart')
            {
                //create new arrays to hold the radar data and labels
                var radarLabels = [];
                var radarData = [];
                var eventAverageRadarData = [];

                //iterate through each stat inside the team specified and store the defense, offense and drive rating
                $.each(jsonResponse, function (key, value)
                {
                    if(key !== 'EventAvg')
                    {
                        $.each(value, function (statKey, statValue)
                        {
                            if (statKey.endsWith('Rating'))
                            {
                                radarLabels.push(statKey);
                                radarData.push(jsonResponse[teamList[0]][statKey]);
                            }
                        });
                    }
                    else if(key === 'EventAvg')
                    {
                        $.each(value, function (statKey, statValue)
                        {
                            if (statKey.endsWith('Rating'))
                                eventAverageRadarData.push(jsonResponse['EventAvg'][statKey]);
                        });
                    }
                });

                if(dodBreakdownChart !== undefined)
                    dodBreakdownChart.destroy();
                dodBreakdownChart = createRadarChart(context, radarLabels, radarData, eventAverageRadarData, 'DOD Ratings');
            }
        });
}

/**
 * Creates a new Chart.js chart as a horizontal bar graph
 * @param context context of the canvas to add the chartbar to
 * @param jsonResponse to display for each team (Item Averages)
 * @param graphItem to display for each team (Item Averages)
 * @param title of the graph
 * @returns Chart
 */
function createChart(context, jsonResponse, graphItem, title)
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
                if (val > average * 1.45)
                    backgroundColors.push('#64FF62');

                //if the average is less than double but still greater to or equal to the event average, that's a warning (yellow) stat
                else if (val >= average)
                    backgroundColors.push('#FFD966');

                //if the average is less than the event average, that's a bad (red) stat
                else
                    backgroundColors.push('#E67C73');
            }

            //match specified, assign colors based on alliance
            else
                backgroundColors.push(averages['AllianceColor'] === 'RED' ? 'rgba(255, 0, 0, 0.5)' : 'rgba(0, 100, 255, 0.5)');

        }
    });

    var xAxesTitle, yAxesTitle;

    //set the titles of the axes
    $.each(GRAPH_PERIODS , function(key, value)
    {
        $.each(value, function (key, value)
        {
            if(graphItem === value)
            {
                if(matchAveragData.length > 0)
                {
                    yAxesTitle = key;
                    xAxesTitle = 'Matches';
                    return;
                }
                else
                {
                    xAxesTitle = (matchId === '' ? 'Average ' : '') + key;
                    yAxesTitle = 'Teams';
                    return;
                }

            }
        });
    });

    xAxesTitle = xAxesTitle.toUpperCase();
    yAxesTitle = yAxesTitle.toUpperCase();

    var maxData = Math.max.apply(null, graphData);
    var minData = Math.min.apply(null, graphData);

    var maxData2 = Math.max.apply(null, matchAveragData);
    var minData2 = Math.min.apply(null, matchAveragData);

    maxData = ((maxData > maxData2) ? maxData : maxData2);
    minData = ((minData < minData2) ? minData : minData2);

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
                title:
                    {
                        //show graph title
                        display: true,
                        text: title
                    },
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
                                max: ((!graphItem.endsWith('Rating')) ? maxData * 1.05 : 5),
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
                            content: (matchId === '' ? 'Event  ' : 'Match ') + 'Average ' + Math.round(average * 100.00) / 100.00
                        }
                    }]
                }
            }

        });
}

/**
 * Creates a new Chart.js chart as a radar graph to display defense and offense stats
 * @param context context of the canvas to add the chartbar to
 * @param labels all Y axis labels (Team Ids)
 * @param data to display for each team (Item Averages)
 * @param data2 to display for event averages
 * @param title of the graph
 * @returns Chart
 */
function createRadarChart(context, labels, data, data2, title)
{

    return new Chart(context,
        {
            //graph type
            type: 'radar',
            data:
                {
                    //team ids
                    labels: labels,
                    datasets:
                        [
                            {
                                //item averages
                                label: 'Team Average',
                                data: data,

                                //stat colors
                                backgroundColor: 'rgba(3, 169, 244, 0.2)',
                                borderColor: ['#03A9F4']
                            },
                            {
                                label: 'Event Average',

                                //Event averages
                                data: data2,

                                //stat colors
                                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                                borderColor: ['#F00']
                            }
                        ]
                },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem)
                        {
                            //tooltip title
                            return tooltipItem.yLabel;
                        }
                    }
                },
                title:
                    {
                        //show graph title
                        display: true,
                        text: title
                    },
                scale:
                    {
                        ticks:
                            {
                                beginAtZero: true,
                                max: 5
                            }
                    },
                maintainAspectRatio: false,
                responsive: true,
            }

        });
}
