<?php

class RobotInfoArray extends \ArrayObject implements ArrayAccess
{

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        $robotInfoArray = array();
        $robotInfoKeyStates = RobotInfoKeys::getRobotInfoKeyStates();

        //setup the 'fake' object to display it to html
        //array format is $array[YEAR][EVENT][TEAM][STATE][NAME] = value
        //ex $array[2019][2019onwin][5885][PreGame][RobotWidth] = 5.3 feet
        foreach($this as $robotInfo)
            $robotInfoArray[$robotInfo->YearId][$robotInfo->EventId][$robotInfo->TeamId][$robotInfo->PropertyState][$robotInfo->PropertyKey] = $robotInfo->PropertyValue;


        //first iterate through each year
        foreach($robotInfoArray as $yearInfo)
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

                    //for each of the robot info key states (pre game, post game, auto, teleop etc..) get the value from the team we are currently viewing
                    //and add a new field for it
                    foreach ($robotInfoKeyStates as $stateKey)
                    {
                        $html .=
                            '<strong style="padding-left: 40px;">' . $stateKey . '</strong>
                            <div class="mdl-card__supporting-text">';

                        foreach ($teamInfo[$stateKey] as $propertyValueKey => $propertyValueValue)
                        {

                            $html .=
                                '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input disabled class="mdl-textfield__input" type="text" value="' . $propertyValueValue . '" name="completedBy">
                                        <label class="mdl-textfield__label" >' . $propertyValueKey . '</label>
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