<?php
require_once("../../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/Ajax.php");

$eventId = $_GET['eventId'];

$event = Events::withId($eventId);
?>

<!doctype html>
<html lang="en">
<head>

    <title>Stats</title>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>

    <script src="<?php echo JS_URL ?>Chart.min.js"></script>
    <link rel="stylesheet" href="<?php echo CSS_URL ?>Chart.min.css">
    <script src="<?php echo JS_URL ?>chartjs-plugin-annotation.min.js"></script>

</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Stats', STATS_URL . 'stats?eventId=' . $event->BlueAllianceId, true);

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($event->Name, null, $navBar, $event, null, ADMIN_URL . 'list?yearId=' . $event->YearId);

    echo $header->toHtml();

    ?>

    <main class="mdl-layout__content">
        <div class="stats-search-wrapper">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label stats-search">
                <input id="teamSearch" class="mdl-textfield__input" type="text" placeholder="1114, 2056, 5885...">
                <label class="mdl-textfield__label">Search</label>
            </div>
            <div id="team-chips">
            </div>
        </div>




        <div class="content-grid mdl-grid">

            <?php

            $keys = ScoutCardInfoKeys::getKeys(null, $event);

            $keyStates = array();

            foreach ($keys as $key)
                if($key->IncludeInStats)
                {
                    $keyStr = str_replace(' ', '', $key->KeyState);
                    $keyStates[$keyStr] = $key->KeyState;
                }

            foreach ($keyStates as $keyState => $placeholder)
            {
                $keyState = str_replace(' ', '', $keyState);
            ?>
                <div class="mdl-cell stats-cell">
                    <p><?php echo $placeholder ?></p>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input hidden class="mdl-textfield__input" type="text" value="placeholder">
                        <select class="mdl-textfield__input" id="<?php echo 'change' . $keyState . 'Item' ?>" name="<?php echo 'change' . $keyState . 'Item' ?>">
                        </select>
                        <label class="mdl-textfield__label" for="<?php echo 'change' . $keyState . 'Item' ?>">Item</label>
                    </div>
                    <div class="stats-chart">
                        <canvas id="<?php echo $keyState . 'Chart' ?>"></canvas>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>

        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>

</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
<script defer src="<?php echo JS_URL ?>stat-charts.js.php?eventId=<?php echo $event->BlueAllianceId ?>"></script>

<script defer>
    $(document).ready(function()
    {
        //get data from the ajax script
        $.post('<?php echo AJAX_URL ?>autocomplete.php',
            {
                action: 'event_team_list',
                eventId: '<?php echo $event->BlueAllianceId ?>'
            },
            function(data)
            {
                var parsedData = JSON.parse(data);

                if (parsedData['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                {
                   $( "#teamSearch" ).autocomplete({
                        source: parsedData['<?php echo Ajax::$RESPONSE_KEY ?>'],
                      select: function( event, ui )
                      {
                          event.preventDefault();
                          var selectedObj = ui.item;
                          $("#teamSearch").val("");

                          $("#team-chips").append(createTeamChip(selectedObj.number));
                          searchTeams();
                      }
                   });
                }
            });
    });


    function createTeamChip(teamNumber)
    {
        return "" +
            "<span class=\"material-side-padding mdl-chip mdl-chip--deletable\">" +
            "   <span class=\"mdl-chip__text\">" + teamNumber + "</span>" +
            "   <button onclick=\"console.log($(this).parent().remove()); searchTeams();\" type=\"button\" class=\"mdl-chip__action\"><i class=\"material-icons\">cancel</i></button>" +
            "</span>";
    }
</script>

</body>
</html>
