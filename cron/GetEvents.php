<?php
require_once('../config.php');
require_once('../classes/tables/core/Events.php');

set_time_limit(600);

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

//cleanup duplicates
$database = new Database();
$database->query("DELETE event1 FROM events event1, events event2 WHERE event1.Id < event2.Id AND event1.BlueAllianceId = event2.BlueAllianceId;");
$database->close();

//echo serialize(getEvents());

/**
 * Queries the blue alliance API for events on a specific team
 * @return mixed
 */
function getEvents()
{
    $url = "https://www.thebluealliance.com/api/v3/events/" . date("Y") . "?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);

    return json_decode($response, true);
}

?>