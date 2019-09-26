<?php
require_once('../config.php');
require_once('../classes/tables/core/Teams.php');
require_once('../classes/tables/core/Events.php');
require_once('../classes/tables/core/EventTeamList.php');

set_time_limit(600);

$database = new Database('core');
$events = $database->query("DELETE FROM teams");
unset($database);

$teamIds = array();

for($i = 0; $i < 17; $i++)
{
    $teamIds = getTeams($i, $teamIds);
}

function getTeams($pageNum, $teamIds)
{
    $url = "https://www.thebluealliance.com/api/v3/teams/$pageNum?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

    echo $url . '<br>';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);

    $jsonObj = json_decode($response);
    foreach ($jsonObj as $obj)
    {
        if(!in_array($obj->team_number, $teamIds) && !empty($obj->country))
        {
            $team = new Teams();
            $team->Id = $obj->team_number;
            $team->Name = $obj->nickname;
            $team->City = $obj->city;
            $team->StateProvince = $obj->state_prov;
            $team->Country = $obj->country;
            $team->RookieYear = $obj->rookie_year;
            $team->WebsiteURL = $obj->website;
            $team->ImageFileURI = "";

            $team->save();
            $teamIds[] = $obj->team_number;
        }
    }

    return $teamIds;

}

?>