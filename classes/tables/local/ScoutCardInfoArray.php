<?php

class ScoutCardInfoArray extends \ArrayObject implements ArrayAccess
{

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        $scoutCardInfoArray = array();
        $scoutCardInfoKeyStates = array();
        $matchId = null;

        require_once(ROOT_DIR . "/classes/tables/core/Years.php");
        require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");

        for($i = 0; $i < sizeof($this) && empty($yearId); $i++)
        {
            $yearId = $this[$i]->YearId;
        }

        $year = Years::withId($yearId);
        $scoutCardInfoKeys = ScoutCardInfoKeys::getKeys($year);

        //setup the 'fake' object to display it to html
        //array format is $array[YEAR][EVENT][TEAM][STATE][NAME] = value
        //ex $array[2019][2019onwin][5885][PreGame][RobotWidth] = 5.3 feet
        foreach($this as $scoutCardInfo)
        {
            if(empty($matchId))
                $matchId = $scoutCardInfo->MatchId;

            $scoutCardInfoKey = null;

            for($i = 0; $i < sizeof($scoutCardInfoKeys) && empty($scoutCardInfoKey); $i++)
            {
                if($scoutCardInfo->PropertyKeyId == $scoutCardInfoKeys[$i]->Id)
                    $scoutCardInfoKey = $scoutCardInfoKeys[$i];
            }

            $scoutCardInfoArray[$scoutCardInfo->YearId][$scoutCardInfo->EventId][$scoutCardInfo->TeamId][$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName] = $scoutCardInfo->PropertyValue;
        }

        //get the keys for the specified year and store the states for sections
        foreach($scoutCardInfoKeys as $scoutCardInfoKey)
            $scoutCardInfoKeyStates[] = $scoutCardInfoKey->KeyState;
        $scoutCardInfoKeyStates = array_unique($scoutCardInfoKeyStates);

        //first iterate through each year
        foreach($scoutCardInfoArray as $yearInfo)
        {
            //then iterate through each event
            foreach($yearInfo as $eventId => $eventInfo)
            {
                //for each event, iterate through each team and add the html for a new card
                foreach($eventInfo as $teamId => $teamInfo)
                {
                    ?>
                        <div class="mdl-layout__tab-panel is-active">
                            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                                <div class="mdl-card mdl-cell mdl-cell--12-col">
                                    <h4 style="padding-left: 40px;"><?php echo Teams::withId($teamId)->toString() ?></h4>
                    <?php
                    //for each of the scout card info key states (pre game, post game, auto, teleop etc..) get the value from the team we are currently viewing
                    //and add a new field for it
                    foreach ($scoutCardInfoKeyStates as $stateKey)
                    {
                        ?>
                                    <div class="mdl-card__supporting-text" style="margin: 0 40px !important;">
                                        <h5><?php echo $stateKey ?></h5>
                                        <hr>
                        <?php

                        foreach (ScoutCardInfoKeys::getKeys($year, null, $stateKey) as $scoutCardInfoKey)
                        {

                            ?>
                                        <strong class="setting-title"><?php echo $scoutCardInfoKey->KeyName ?></strong>
                                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                            <input class="mdl-textfield__input" value="<?php echo (($scoutCardInfoKey->DataType == DataTypes::BOOL) ? (($teamInfo[$stateKey][$scoutCardInfoKey->KeyName] == 1) ? 'Yes' : 'No') : $teamInfo[$stateKey][$scoutCardInfoKey->KeyName]) ?>">
                                        </div>
                            <?php
                        }

                        ?>
                                    </div>
                        <?php
                    }

                    ?>
                                    <div class="card-buttons">
                                        <button onclick="deleteRecord('<?php echo ScoutCardInfo::class ?>', -1, {teamId: <?php echo $teamId ?>, eventId: '<?php echo $eventId ?>', matchId: '<?php echo $matchId ?>'})" class="mdl-button mdl-js-button mdl-js-ripple-effect">
                                            <span class="button-text">Delete</span>
                                        </button>
                                        <button onclick="saveRecord('<?php echo ScoutCardInfo::class ?>', <?php echo $teamId ?>)" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
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