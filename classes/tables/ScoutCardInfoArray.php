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

        require_once(ROOT_DIR . "/classes/tables/Years.php");
        require_once(ROOT_DIR . "/classes/tables/ScoutCardInfoKeys.php");

        //setup the 'fake' object to display it to html
        //array format is $array[YEAR][EVENT][TEAM][STATE][NAME] = value
        //ex $array[2019][2019onwin][5885][PreGame][RobotWidth] = 5.3 feet
        foreach($this as $scoutCardInfo)
        {
            //retrieve the year in question from the stored array
            if(empty($yearId))
                $yearId = $scoutCardInfo->YearId;

            $scoutCardInfoArray[$scoutCardInfo->YearId][$scoutCardInfo->EventId][$scoutCardInfo->TeamId][$scoutCardInfo->PropertyState][$scoutCardInfo->PropertyKey] = $scoutCardInfo->PropertyValue;
        }

        $year = Years::withId($yearId);

        //get the keys for the specified year and store the states for sections
        foreach(ScoutCardInfoKeys::getKeys($year) as $scoutCardInfoKey)
            $scoutCardInfoKeyStates[] = $scoutCardInfoKey->KeyState;
        $scoutCardInfoKeyStates = array_unique($scoutCardInfoKeyStates);

        //first iterate through each year
        foreach($scoutCardInfoArray as $yearInfo)
        {
            //then iterate through each event
            foreach($yearInfo as $eventInfo)
            {
                //for each event, iterate through each team and add the html for a new card
                foreach($eventInfo as $teamId => $teamInfo)
                {
                    $team = Teams::withId($teamId);

                    $html = '
                        <div class="mdl-layout__tab-panel is-active" id="overview">
                            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                                <div class="mdl-card mdl-cell mdl-cell--12-col">
                                <h4 style="padding-left: 40px;">' . $team->toString() . '</h4>
                                    <form method="post" action="' . $_SERVER['REQUEST_URI'] . '" id="scout-card-form">';

                    //for each of the scout card info key states (pre game, post game, auto, teleop etc..) get the value from the team we are currently viewing
                    //and add a new field for it
                    foreach ($scoutCardInfoKeyStates as $stateKey)
                    {
                        $html .=
                            '<strong style="padding-left: 40px;">' . $stateKey . '</strong>
                            <div class="mdl-card__supporting-text">';

                        foreach (ScoutCardInfoKeys::getKeys($year, null, $stateKey) as $scoutCardInfoKey)
                        {

                            $html .=
                                '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input disabled class="mdl-textfield__input" type="text" value="' . (($scoutCardInfoKey->DataType == DataTypes::BOOL) ? (($teamInfo[$stateKey][$scoutCardInfoKey->KeyName] == 1) ? 'Yes' : 'No') : $teamInfo[$stateKey][$scoutCardInfoKey->KeyName]) . '" name="completedBy">
                                        <label class="mdl-textfield__label" >' . $scoutCardInfoKey->KeyName . '</label>
                                    </div>';
                        }

                        $html .=
                            '</div>';
                    }

                    $html .=
                                '</form>
                                </div>
                            </section>
                        </div>';
                }
            }
        }

        return $html;
    }
}