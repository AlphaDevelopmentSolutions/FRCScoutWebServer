<?php
require_once("config.php");
require_once(ROOT_DIR . "/classes/Teams.php");
require_once(ROOT_DIR . "/classes/Events.php");


$eventId = $_GET['eventId'];
$teamId = $_GET['teamId'];

$team = Teams::withId($teamId);
$event = Events::withId($eventId);
$pitCard = $team->getPitCards($event)[0];

$url = "http://scouting.wiredcats5885.ca/ajax/GetOPRStats.php";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    "eventCode=" . $event->BlueAllianceId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
$stats = json_decode($response, true);

$opr = $stats['oprs']['frc' . $pitCard->TeamId];
$dpr = $stats['dprs']['frc' . $pitCard->TeamId];
$ccwms = $stats['ccwms']['frc' . $pitCard->TeamId];

?>

<!doctype html>
<html lang="en">
  <head>
    <title><?php echo $team->Id . ' - ' . $team->Name ?></title>
    <?php require_once('includes/meta.php') ?>
      <script src="<?php echo URL_PATH ?>/js/Chart.min.js"></script>
      <link rel="stylesheet" href="<?php echo URL_PATH ?>/css/Chart.min.css">
      <script src="<?php echo URL_PATH ?>/js/chartjs-plugin-annotation.min.js"></script>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <?php
        $navBarArray = new NavBarArray();

        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Teams', '/team-list.php?eventId=' . $event->BlueAllianceId);
        $navBarLinksArray[] = new NavBarLink('Team ' . $teamId, '', true);

        $navBarArray[] = new NavBar($navBarLinksArray);

        $navBarLinksArray = new NavBarLinkArray();
        $navBarLinksArray[] = new NavBarLink('Matches', '/team-matches.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
        $navBarLinksArray[] = new NavBarLink('Pits', '/team-pits.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
        $navBarLinksArray[] = new NavBarLink('Photos', '/team-photos.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id);
        $navBarLinksArray[] = new NavBarLink('Stats', '/team-stats.php?eventId=' . $event->BlueAllianceId . '&teamId=' . $team->Id, true);

        $navBarArray[] = new NavBar($navBarLinksArray);

        $additionContent = '';

        $profileMedia = $team->getProfileImage();

        if(!empty($profileMedia->FileURI))
        {
            $additionContent .=
                '<div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                  <div class="circle-image" style="background-image: url(' . ROBOT_MEDIA_URL . $profileMedia->FileURI . ')">

                  </div>
                </div>';
        }

        $additionContent .=
            '
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            <h3>' . $team->Id . ' - ' . $team->Name . '</h3><br>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            <h3>' . $team->City . ', ' . $team->StateProvince . ', ' . $team->Country . '</h3><br>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">';


        if(!empty($team->FacebookURL))
        {
            $additionContent .=
                '
                    <a target="_blank" href="https://www.facebook.com/' . $team->FacebookURL . '">
                        <i class="fab fa-facebook-f header-icon"></i>
                    </a>
                  ';
        }

        if(!empty($team->TwitterURL))
        {
            $additionContent .=
                '
                    <a target="_blank" href="https://www.twitter.com/' . $team->TwitterURL . '">
                        <i class="fab fa-twitter header-icon"></i>
                    </a>
                  ';
        }

        if(!empty($team->InstagramURL))
        {
            $additionContent .=
                '
                    <a target="_blank" href="https://www.instagram.com/' . $team->InstagramURL . '">
                        <i class="fab fa-instagram header-icon"></i>
                    </a>
                  ';
        }

        if(!empty($team->YoutubeURL))
        {
            $additionContent .=
                '
                    <a target="_blank" href="https://www.youtube.com/' . $team->YoutubeURL . '">
                        <i class="fab fa-youtube header-icon"></i>
                    </a>
                  ';
        }

        if(!empty($team->WebsiteURL))
        {
            $additionContent .=
                '
                    <a target="_blank" href="' . $team->WebsiteURL . '">
                        <i class="fas fa-globe header-icon"></i>
                    </a>
                  ';
        }

        $additionContent .=
            '
            </div>
            <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                <h6 style="margin: unset"><strong>OPR:</strong>' . round($opr, 2) . '</h6>
            </div>
    
            <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                <h6 style="margin: unset"><strong>DPR:</strong>' . round($dpr, 2) . '</h6>
            </div>
            <div id="quick-stats" style="padding-left: 40px" hidden>
                <h6 style="margin: unset"><strong>Drivetrain:</strong>' . $pitCard->DriveStyle . '</h6>
                <h6 style="margin: unset"><strong>Robot Weight:</strong>' . $pitCard->RobotWeight . '</h6>
                <h6 style="margin: unset"><strong>Robot Length:</strong>' . $pitCard->RobotLength . '</h6>
                <h6 style="margin: unset"><strong>Robot Width:</strong>' . $pitCard->RobotWidth . '</h6>
                <h6 style="margin: unset"><strong>Robot Height:</strong>' . $pitCard->RobotHeight . '</h6>
    
                <h6 style="margin: unset"><strong>Auto Exit Habitat:</strong>' . $pitCard->AutoExitHabitat . '</h6>
                <h6 style="margin: unset"><strong>Auto Hatch Panels:</strong>' . $pitCard->AutoHatch . '</h6>
                <h6 style="margin: unset"><strong>Auto Cargo:</strong>' . $pitCard->AutoCargo . '</h6>
    
                <h6 style="margin: unset"><strong>Teleop Hatch:</strong>' . $pitCard->TeleopHatch . '</h6>
                <h6 style="margin: unset"><strong>Teleop Cargo:</strong>' . $pitCard->TeleopCargo . '</h6>
    
                <h6 style="margin: unset"><strong>Return To Habitat:</strong>' . $pitCard->ReturnToHabitat . '</h6>
    
                <h6 style="margin: unset"><strong>Notes:</strong>' . $pitCard->Notes . '</h6>
                <h6 style="margin: unset"><strong>Completed By:</strong>' . $pitCard->CompletedBy . '</h6>
            </div>
            <div style="height: unset" class="mdl-layout--large-screen-only mdl-layout__header-row">
                <h6 style="margin: unset" ><a id="show-stats-btn" href="#" style="color:white" onclick="showQuickStats()">Show More</a></h6>
            </div>
            <div class="mdl-layout--large-screen-only mdl-layout__header-row"></div>';

        $header = new Header($event->Name, $additionContent, $navBarArray, $event->BlueAllianceId);

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
                  <div style="height: 400px;">
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
                  <div style="height: 400px;">
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
                  <div style="height: 400px;">
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
                  <div style="height: 400px;">
                      <canvas id="postGameChart"></canvas>
                  </div>
              </div>
          </div>
      </main>
    </div>
    <?php require_once('includes/bottom-scripts.php') ?>

  <script>
      function showQuickStats()
      {

          if($('#quick-stats').attr('hidden'))
          {
              $('#show-stats-btn').html('Show Less');
              $('#quick-stats').removeAttr('hidden');
          }

          else
          {
              $('#show-stats-btn').html('Show More');
              $('#quick-stats').attr('hidden', 'hidden');
          }

      }
  </script>

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
            $.post('<?php echo URL_PATH ?>/ajax/ajax.php',
                {
                    action: 'load_stats',
                    eventId:'<?php echo $event->BlueAllianceId; ?>',
                    teamId: '<?php echo $team->Id ?>'
                },
                function(data)
                {
                    //parse the response data into JSON
                    var jsonResponse = JSON.parse(data);

                    var labels = []; //labels AKA team numbers to show on the Y axis
                    var data = []; //data AKA item averages to show on the graph
                    var backgroundColors = []; //colors to indicate bad/warning/good stats
                    var average = jsonResponse['EventAvg'][graphItem]; //get the event average

                    console.log(jsonResponse);

                    //for each item (team) inside the graph data, calculate and store the averages
                    $.each(jsonResponse, function(matchId, averages)
                    {
                        //dont add row for event avg
                        if(matchId !== 'EventAvg')
                        {
                            var val = averages[graphItem]; //store the value of the teams averages for the specified item

                            labels.push('Quals ' + matchId); //add the team id to the label
                            data.push(val); //add the item average to the data
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
            var maxData = Math.max.apply(null, data);
            var minData = Math.min.apply(null, data);

            return new Chart(context,
                {
                    //graph type
                    type: 'line',
                    data:
                        {
                            //team ids
                            labels: labels,
                            datasets: [{
                                //item averages
                                data: data,

                                borderColor: ['#03A9F4']
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
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    max: maxData + 1,
                                    min: ((minData === 0) ? minData : minData - 1)
                                }
                            }]
                        },
                        annotation: {
                            //adds the event average vertical line
                            annotations: [{
                                type: 'line',
                                mode: 'horizontal',
                                scaleID: 'y-axis-0',
                                value: average,
                                borderColor: '#0288D1',
                                borderWidth: 4,
                                label: {
                                    enabled: true,
                                    content: 'Average ' + Math.round(average * 100.00) / 100.00
                                }
                            }]
                        }
                    }

                });
        }
    </script>
  </body>
</html>
