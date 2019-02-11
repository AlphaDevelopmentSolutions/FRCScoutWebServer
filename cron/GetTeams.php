<?php
require_once('../config.php');
require_once('../classes/Teams.php');

$database = new Database();
$database->query("DELETE FROM teams;");
$events = $database->query("SELECT BlueAllianceId FROM events");
$database->close();

if($events && $events->num_rows > 0)
{
    while ($row = $events->fetch_assoc())
    {
        getTeams($row['BlueAllianceId']);
    }
}

function getTeams($eventCode)
{
    $url = "https://www.thebluealliance.com/api/v3/event/" . $eventCode . "/teams?X-TBA-Auth-Key=gGDqr1h7gbcdKAumaFgnuzPJYDox7vz6gyX1a8r9nA0VPPLYBD8q1Uj8byvUR5Lp";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);

    $jsonObj = json_decode($response);
    foreach ($jsonObj as $obj)
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

        $url = "https://www.thebluealliance.com/api/v3/team/" . $obj->key . "/social_media?X-TBA-Auth-Key=gGDqr1h7gbcdKAumaFgnuzPJYDox7vz6gyX1a8r9nA0VPPLYBD8q1Uj8byvUR5Lp";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);

        foreach (json_decode($response) as $obj2)
        {
            if (strpos($obj2->type, 'facebook') !== false) {
                $team->FacebookURL = 'https://www.facebook.com/' . $obj2->foreign_key;
            } else if (strpos($obj2->type, 'twitter') !== false) {
                $team->TwitterURL = 'https://www.twitter.com/' . $obj2->foreign_key;
            } else if (strpos($obj2->type, 'instagram') !== false) {
                $team->InstagramURL = 'https://www.instagram.com/' . $obj2->foreign_key;
            } else if (strpos($obj2->type, 'youtube') !== false) {
                $team->YoutubeURL = 'https://www.youtube.com/' . $obj2->foreign_key;
            }
        }

        $team->save();
    }
}

?>