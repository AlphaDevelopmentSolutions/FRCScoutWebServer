<?php

class EventTeamList extends CoreTable
{
    public $Id;
    public $TeamId;
    public $EventId;

    public static $TABLE_NAME = 'event_team_list';

    /**
     * @param null | Events $event if specified, filters by id
     * @param CoreDatabase $database
     * @return EventTeamList[]
     */
    public static function getObjects($database, $event = null)
    {
        $whereStatment = "";
        $cols = array();
        $args = array();

        if(!empty($event))
        {
            $whereStatment = "! = ?";
            $cols[] = "EventId";
            $args[] = $event->BlueAllianceId;
        }


        return parent::getObjects($database, $whereStatment, $cols, $args);
    }

    public function toHtml()
    {
        // TODO: Implement toHtml() method.
    }

    public function toString()
    {
        // TODO: Implement toString() method.
    }
}

?>