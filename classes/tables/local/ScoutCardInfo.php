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
     * @param boolean $asNormalArray if true, uses [array] instead of [ScoutCardInfoArray]
     * @return ScoutCardInfoArray | ScoutCardInfo[]
     */
    public static function getObjects($scoutCardInfoKey = null, $year = null, $event = null, $match = null, $team = null, $asNormalArray = false)
    {
        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfoArray.php');

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

        $objs = parent::getObjects($whereStatment, $cols, $args);


        if($asNormalArray)
            return $objs;

        else
        {
            $returnArray = new ScoutCardInfoArray();

            foreach($objs as $obj)
            {
                $returnArray[] = $obj;
            }

            return $returnArray;
        }
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