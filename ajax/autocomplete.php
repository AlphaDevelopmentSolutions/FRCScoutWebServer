<?php
require_once("../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/EventTeamList.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/Ajax.php");

$ajax = new Ajax();

switch ($_POST['action'])
{
    /**
     * Gets all teams in the DB
     */
    case 'team_list':
        $return_array = array();

        foreach(Teams::getObjects(null, null, null,'Id', 'DESC') as $team)
        {
            $return_array[] = [
                "label" => "$team->Id - $team->Name",
                "number" => "$team->Id",
                "name" => "$team->Name"
            ];
        }


        sort($return_array);
        $ajax->success($return_array);

        break;

    /**
     * Filters all teams from an event
     */
    case 'event_team_list':
        $return_array = array();
        $teamIds = array();
        $event = Events::withId($_POST['eventId']);

        foreach (EventTeamList::getObjects($event) as $eventTeamListItem)
        {
            $teamIds[] = $eventTeamListItem->TeamId;
        }

        foreach(Teams::getObjects(null, null, null, 'Id', 'DESC') as $team)
        {
            if(in_array($team->Id, $teamIds))
                $return_array[] = [
                    "label" => "$team->Id - $team->Name",
                    "number" => "$team->Id",
                    "name" => "$team->Name"
                ];
        }

        sort($return_array);
        $ajax->success($return_array);

        break;
}