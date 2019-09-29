<?php

class RobotInfoArray extends \ArrayObject implements ArrayAccess
{

    /**
     * Displays the object once converted into HTML
     */
    public function toHtml()
    {
        $robotInfoArray = array();
        $robotInfoKeyStates = array();

        require_once(ROOT_DIR . "/classes/tables/core/Years.php");

        for($i = 0; $i < sizeof($this) && empty($yearId); $i++)
        {
            $yearId = $this[$i]->YearId;
        }

        $year = Years::withId($yearId);
        $robotInfoKeys = RobotInfoKeys::getKeys($year);

        //setup the 'fake' object to display it to html
        //array format is $array[YEAR][EVENT][TEAM][STATE][NAME] = value
        //ex $array[2019][2019onwin][5885][PreGame][RobotWidth] = 5.3 feet
        foreach($this as $robotInfo)
        {
            $robotInfoKey = null;

            for($i = 0; $i < sizeof($robotInfoKeys) && empty($robotInfoKey); $i++)
            {
                if($robotInfo->PropertyKeyId == $robotInfoKeys[$i]->Id)
                    $robotInfoKey = $robotInfoKeys[$i];
            }

            $robotInfoArray[$robotInfo->YearId][$robotInfo->EventId][$robotInfo->TeamId][$robotInfoKey->KeyState][$robotInfoKey->KeyName] = $robotInfo->PropertyValue;
        }

        $year = Years::withId($yearId);

        //get the keys for the specified year and store the states for sections
        foreach($robotInfoKeys as $robotInfoKey)
            $robotInfoKeyStates[] = $robotInfoKey->KeyState;
        $robotInfoKeyStates = array_unique($robotInfoKeyStates);

        //first iterate through each year
        foreach($robotInfoArray as $yearInfo)
        {
            //then iterate through each event
            foreach($yearInfo as $eventId => $eventInfo)
            {
                //for each event, iterate through each team and add the html for a new card
                foreach($eventInfo as $teamId => $teamInfo)
                {
                    ?>
                        <div class="mdl-layout__tab-panel is-active" id="overview">
                            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                                <div class="mdl-card mdl-cell mdl-cell--12-col">
                                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">

                    <?php

                    //for each of the robot info key states (pre game, post game, auto, teleop etc..) get the value from the team we are currently viewing
                    //and add a new field for it
                    foreach ($robotInfoKeyStates as $stateKey)
                    {
                        ?>
                                        <div class="mdl-card__supporting-text">
                                            <h5><?php echo $stateKey ?></h5>
                                            <hr>
                        <?php

                        foreach (RobotInfoKeys::getKeys($year, null, $stateKey) as $robotInfoKey)
                        {

                            ?>
                                            <strong class="setting-title"><?php echo $robotInfoKey->KeyName ?></strong>
                                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                                <input class="mdl-textfield__input" value="<?php echo $teamInfo[$stateKey][$robotInfoKey->KeyName] ?>">
                                            </div>

                            <?php
                        }

                        ?>
                                        </div>
                        <?php
                    }
                    ?>
                                    </form>
                                    <div class="card-buttons">
                                        <button onclick="deleteRecord('<?php echo RobotInfo::class ?>', -1, {teamId: <?php echo $teamId ?>, eventId: '<?php echo $eventId ?>'})" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                            <span class="button-text">Delete</span>
                                        </button>
                                        <button style="width: 95px; margin: 24px;" onclick="saveRecord('<?php echo RobotInfo::class ?>', <?php echo $teamId ?>)" class="center-div-horizontal-inner mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                                            <span class="button-text">Save</span>
                                        </button>
                                    </div>
                                    </div>
                                </section>
                            </div>
                    <?php
                }
            }
        }
    }
}