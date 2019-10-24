<?php
if(php_sapi_name() != 'cli')
    header("HTTP/1.0 401");
else
{
    $bypassCoreCheck = true;
    require_once('../config.php');
    require_once(ROOT_DIR . '/classes/tables/core/Matches.php');
    require_once(ROOT_DIR . '/classes/tables/core/Events.php');
    require_once(ROOT_DIR . '/classes/tables/core/Years.php');

    set_time_limit(600);

    $yearId = empty($argv[1]) ? readline("Enter Year: ") : $argv[1];

    $database = new Database('core');
    $database->query("delete from matches where eventid like '%$yearId%';");
    unset($database);

    $events = Events::getObjects($coreDb, Years::withId($coreDb, $yearId));
    $eventsSize = sizeof($events);
    for($i = 0; $i < $eventsSize; $i++)
    {
        $event = $events[$i];
        $percent = round($i / $eventsSize, 2) * 100;

        echo "$i / {$eventsSize} - {$percent}% - Getting matches for event {$event->toString()}...\n";

        $url = "https://www.thebluealliance.com/api/v3/event/" . $event->BlueAllianceId . "/matches/simple?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);

        $jsonObj = json_decode($response, true);

        foreach ($jsonObj as $obj)
        {
            if (empty($obj[0]['event_id']))
            {
                $match = new Matches();

                $match->Date = date('Y-m-d H:i:s', $obj['predicted_time']);
                $match->EventId = $obj['event_key'];
                $match->MatchType = $obj['comp_level'];
                $match->Key = $obj['key'];
                $match->MatchNumber = $obj['match_number'];
                $match->SetNumber = $obj['set_number'];
                $match->BlueAllianceTeamOneId = substr($obj['alliances']['blue']['team_keys'][0], 3);
                $match->BlueAllianceTeamTwoId = substr($obj['alliances']['blue']['team_keys'][1], 3);
                $match->BlueAllianceTeamThreeId = substr($obj['alliances']['blue']['team_keys'][2], 3);
                $match->RedAllianceTeamOneId = substr($obj['alliances']['red']['team_keys'][0], 3);
                $match->RedAllianceTeamTwoId = substr($obj['alliances']['red']['team_keys'][1], 3);
                $match->RedAllianceTeamThreeId = substr($obj['alliances']['red']['team_keys'][2], 3);
                $match->BlueAllianceScore = $obj['alliances']['blue']['score'];
                $match->RedAllianceScore = $obj['alliances']['red']['score'];

                $match->save($coreDb);
            }
        }
    }
}

?>