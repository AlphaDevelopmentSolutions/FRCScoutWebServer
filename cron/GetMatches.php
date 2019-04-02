<?php
require_once('../config.php');
require_once(ROOT_DIR . '/classes/Matches.php');

$database = new Database();
$events = $database->query("SELECT BlueAllianceId FROM events");
$database->close();

if($events && $events->num_rows > 0)
{
    while ($row = $events->fetch_assoc())
    {
        getMatches($row['BlueAllianceId']);
    }
}

//cleanup duplicates
$database = new Database();
$database->query("DELETE matches1 FROM matches matches1, matches matches2 WHERE matches1.Id < matches2.Id AND matches1.Key = matches2.Key;");
$database->close();

function getMatches($eventCode)
{
    $url = "https://www.thebluealliance.com/api/v3/event/" . $eventCode . "/matches/simple?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);

    $jsonObj = json_decode($response, true);

    foreach ($jsonObj as $obj)
    {
        if(empty($obj[0]['event_id']))
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

            $match->save();
        }
    }
}

?>