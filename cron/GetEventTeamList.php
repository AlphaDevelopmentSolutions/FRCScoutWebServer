<?php
if(php_sapi_name() != 'cli')
    header("HTTP/1.0 401");
else
{
    $bypassCoreCheck = true;
    require_once('../config.php');
    require_once('../classes/tables/core/Years.php');
    require_once('../classes/tables/core/Teams.php');
    require_once('../classes/tables/core/Events.php');
    require_once('../classes/tables/core/EventTeamList.php');

    set_time_limit(600);

    $yearId = empty($argv[1]) ? readline("Enter Year: ") : $argv[1];

    $coreDb->query('DELETE FROM event_team_list WHERE eventid LIKE "%' . $yearId . '%"');

    $events = Events::getObjects($coreDb, Years::withId($coreDb, $yearId));
    $eventsSize = sizeof($events);

    $coreDb->beginTransaction();
    for($i = 0; $i < $eventsSize; $i++)
    {
        $event = $events[$i];
        $totalPercent = round($i / $eventsSize, 2) * 100;

        $url = "https://www.thebluealliance.com/api/v3/event/" . $event->BlueAllianceId . "/teams?X-TBA-Auth-Key=" . $_SESSION[BLUE_ALLIANCE_KEY];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);

        $eventTeamListJson = json_decode($response);
        $eventTeamListSize = sizeof($eventTeamListJson);
        for($j = 0; $j < $eventTeamListSize; $j++)
        {
            $percent = round($j / $eventTeamListSize, 2) * 100;

            $obj = $eventTeamListJson[$j];
            $eventTeamList = new EventTeamList();
            $eventTeamList->TeamId = $obj->team_number;
            $eventTeamList->EventId = $event->BlueAllianceId;

            echo "$i / {$eventsSize} - {$totalPercent}% - $j / {$eventTeamListSize} - {$percent}% - Adding team {$obj->team_number} to event {$event->toString()}...\n";

            $eventTeamList->save($coreDb);
        }
    }
    $coreDb->commit();
}
?>