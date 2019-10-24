<?php
if(php_sapi_name() != 'cli')
    header("HTTP/1.0 401");
else
{
    $bypassCoreCheck = true;
    require_once('../config.php');
    require_once(ROOT_DIR . "/classes/tables/core/Events.php");

    set_time_limit(600);

    $yearId = empty($argv[1]) ? readline("Enter Year: ") : $argv[1];

    $url = "https://www.thebluealliance.com/api/v3/events/" . $yearId . "?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);

    $events = json_decode($response, true);
    $eventsSize = sizeof($events);
    for($i = 0; $i < $eventsSize; $i++)
    {
        $percent = round($i / $eventsSize, 2) * 100;

        $eventJson = $events[$i];
        $event = new Events();
        $event->YearId = $yearId;
        $event->BlueAllianceId = $eventJson['key'];
        $event->Name = $eventJson['name'];
        $event->City = $eventJson['city'];
        $event->StateProvince = $eventJson['state_prov'];
        $event->Country= $eventJson['country'];
        $event->StartDate = $eventJson['start_date'];
        $event->EndDate = $eventJson['end_date'];

        echo "$i / {$eventsSize} - {$percent}% - Saving event {$event->toString()}...\n";

        $event->save($coreDb);
    }

    //cleanup duplicates
    $database = new Database('core');
    $database->query("DELETE event1 FROM events event1, events event2 WHERE event1.Id < event2.Id AND event1.BlueAllianceId = event2.BlueAllianceId;");
    unset($database);
}
?>