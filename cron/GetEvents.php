<?php
require_once('../config.php');
require_once('../classes/Events.php');

$database = new Database();
$database->query("DELETE FROM events;");
$database->close();

foreach (getEvents() as $eventJson)
{
    $event = new Events();
    $event->BlueAllianceId = $eventJson['key'];
    $event->Name = $eventJson['name'];
    $event->City = $eventJson['city'];
    $event->StateProvince = $eventJson['state_prov'];
    $event->Country= $eventJson['country'];
    $event->StartDate = $eventJson['start_date'];
    $event->EndDate = $eventJson['end_date'];
    $event->save();
}

/**
 * Queries the blue alliance API for events on a specific team
 * @return mixed
 */
function getEvents()
{
    $url = "https://www.thebluealliance.com/api/v3/team/frc" . TEAM_NUMBER . "/events/" . date("Y") . "?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);

    return json_decode($response, true);
}

?>