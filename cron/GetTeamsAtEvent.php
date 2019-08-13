<?php
require_once('../config.php');
require_once('../classes/tables/core/Teams.php');
require_once('../classes/tables/core/Events.php');
require_once('../classes/tables/core/EventTeamList.php');

set_time_limit(600);

$database = new Database('core');
$events = $database->query('DELETE FROM event_team_list');
$database->close();

foreach(Events::getObjects() as $event)
{
    getTeamsAtEvent($event->BlueAllianceId);
}

function getTeamsAtEvent($eventCode)
{
    $url = "https://www.thebluealliance.com/api/v3/event/" . $eventCode . "/teams?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);

    $jsonObj = json_decode($response);
    foreach ($jsonObj as $obj)
    {
        $eventTeamList = new EventTeamList();
        $eventTeamList->TeamId = $obj->team_number;
        $eventTeamList->EventId = $eventCode;
        $eventTeamList->save();
    }
}
?>