<?php

class Events extends CoreTable
{
    public $Id;
    public $BlueAllianceId;
    public $Name;
    public $City;
    public $StateProvince;
    public $Country;
    public $StartDate;
    public $EndDate;
    public $YearId;

    public static $TABLE_NAME = 'events';

    /**
     * Overrides parent withId method and provides a custom column name to use when loading
     * @param CoreDatabase $database
     * @param int|string $id
     * @return Events
     */
    public static function withId($database, $id)
    {
        return parent::withId($database, $id, 'BlueAllianceId');
    }

    /**
     * Retrieves objects from the database
     * @param CoreDatabase $database
     * @param Years | null $year if specified, filters by id
     * @param Teams | null $team if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return Events[]
     */
    public static function getObjects($database, $year = null, $team = null, $orderBy = 'StartDate', $orderDirection = 'DESC')
    {
        $whereStatment = "";
        $cols = array();
        $args = array();

        //if year specified, filter by year
        if(!empty($year))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        //if year specified, filter by team
        if(!empty($team))
        {
            require_once(ROOT_DIR . "/classes/tables/core/EventTeamList.php");

            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! IN (SELECT ! FROM ! WHERE ! = ? GROUP BY !) ";
            $cols[] = 'BlueAllianceId';
            $cols[] = 'EventId';
            $cols[] = EventTeamList::$TABLE_NAME;
            $cols[] = 'TeamId';
            $cols[] = 'EventId';
            $args[] = $team->Id;
        }

        return parent::getObjects($database, $whereStatment, $cols, $args, $orderBy, $orderDirection);
    }

    /**
     * Gets stats for the specified event
     * @param $scoutCardInfoKeys ScoutCardInfoKeys[] to iterate through
     * @param $scoutCardInfos ScoutCardInfo[] to iterate through
     * @return array of data calculated
     */
    public function getStats($scoutCardInfoKeys, $scoutCardInfos)
    {
        $eventStatsArray = array();
        $eventCardArray = array();

        foreach ($scoutCardInfoKeys as $scoutCardInfoKey)
        {
            $arrayKey = $scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName;

            if ($scoutCardInfoKey->IncludeInStats == '1')
            {
                if (!empty($scoutCardInfos))
                {
                    foreach ($scoutCardInfos as $scoutCardInfo)
                    {
                        if ($scoutCardInfo->PropertyKeyId == $scoutCardInfoKey->Id)
                        {
                            $eventStatsArray[$arrayKey] = ((!empty($eventStatsArray[$arrayKey])) ? $eventStatsArray[$arrayKey] + $scoutCardInfo->PropertyValue : $scoutCardInfo->PropertyValue);

                            $tempCardTotal = ((!empty($eventCardArray[$arrayKey])) ? $eventCardArray[$arrayKey] : 0);
                            $eventCardArray[$arrayKey] = (($scoutCardInfoKey->NullZeros == 1 && $scoutCardInfo->PropertyValue == 0) ? $tempCardTotal : $tempCardTotal + 1);
                        }
                    }

                    if (empty($eventStatsArray[$arrayKey]))
                    {
                        $eventStatsArray[$arrayKey] = 0;

                        $tempCardTotal = ((!empty($eventCardArray[$arrayKey])) ? $eventCardArray[$arrayKey] : 0);
                        $eventCardArray[$arrayKey] = (($scoutCardInfoKey->NullZeros == 1) ? $tempCardTotal : $tempCardTotal + 1);
                    }

                } else
                {
                    $eventStatsArray[$arrayKey] = 0;
                    $eventCardArray[$arrayKey] = 0;
                }
            }
        }

        foreach ($eventStatsArray as $key => $stat)
        {
            $tempCardCount = $eventCardArray[$key];

            $eventStatsArray[$key] = (($tempCardCount != 0) ? round($stat / $tempCardCount, 2) : 0);
        }

        return $eventStatsArray;
    }

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        $html =
            '<div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                        <div class="mdl-card__supporting-text">
                            <h4>' . $this->toString() . '</h4>'
                            . $this->City . ', ' . $this->StateProvince . ', ' . $this->Country . '<br><br>'
                            .  date('F j', strtotime($this->StartDate)) . ' to ' . date('F j', strtotime($this->EndDate)) .
                        '</div>
                        <div class="mdl-card__actions">
                            <a href="' . MATCHES_URL . 'list?eventId=' . $this->BlueAllianceId . '" class="mdl-button">View</a>
                        </div>
                    </div>
                </section>
            </div>';

        return $html;
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->Name;
    }

}

?>