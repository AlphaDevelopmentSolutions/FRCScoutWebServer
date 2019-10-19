<?php

class ScoutCardInfo extends LocalTable
{
    public $Id;
    public $YearId;
    public $EventId;
    public $MatchId;
    public $TeamId;
    public $CompletedBy;
    public $PropertyValue;
    public $PropertyKeyId;

    public static $TABLE_NAME = 'scout_card_info';

    /**
     * Retrieves objects from the database
     * @param null | ScoutCardInfoKeys $scoutCardInfoKey if specified, filters by id
     * @param Years | null $year if specified, filters by id
     * @param Events | null $event if specified, filters by id
     * @param Matches | null $match if specified, filters by id
     * @param Teams | null $team if specified, filters by id
     * @return ScoutCardInfo[]
     */
    public static function getObjects($scoutCardInfoKey = null, $year = null, $event = null, $match = null, $team = null)
    {
        $whereStatment = "";
        $cols = array();
        $args = array();

        //if scout card info key specified, filter by scout card info key
        if(!empty($scoutCardInfoKey))
        {
            $whereStatment = "! = ?";
            $cols[] = "PropertyKeyId";
            $args[] = $scoutCardInfoKey->Id;
        }

        //if year specified, filter by year
        if(!empty($year))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        //if event specified, filter by event
        if(!empty($event))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'EventId';
            $args[] = $event->BlueAllianceId;
        }

        //if event specified, filter by event
        if(!empty($match))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'MatchId';
            $args[] = $match->Key;
        }

        //if team specified, filter by team
        if(!empty($team))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        return parent::getObjects($whereStatment, $cols, $args);
    }

    /**
     * Overrides parent save function to overwrite existing records in case of conflicts
     * @return bool
     */
    public function save()
    {
        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/core/Events.php');
        require_once(ROOT_DIR . '/classes/tables/core/Matches.php');
        require_once(ROOT_DIR . '/classes/tables/core/Years.php');
        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfoKeys.php');

        $scoutCardInfoArray = self::getObjects(ScoutCardInfoKeys::withId($this->PropertyKeyId), Years::withId($this->YearId), Events::withId($this->EventId), Matches::withId($this->MatchId), Teams::withId($this->TeamId));

        foreach ($scoutCardInfoArray as $scoutCardInfo)
        {
            $this->Id = $scoutCardInfo->Id;
        }

        return parent::save();
    }

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        return '';
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->PropertyValue;
    }
}

?>