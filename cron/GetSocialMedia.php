<?php
require_once('../config.php');
require_once('../classes/tables/core/Teams.php');
require_once('../classes/tables/core/Events.php');
require_once('../classes/tables/core/EventTeamList.php');

set_time_limit(600);

$teamIds = array();

foreach(Teams::getObjects() as $team)
{
    getSocialMedia($team);
}

function getSocialMedia($team)
{
    $url = "https://www.thebluealliance.com/api/v3/team/frc" . $team->Id . "/social_media?X-TBA-Auth-Key=gGDqr1h7gbcdKAumaFgnuzPJYDox7vz6gyX1a8r9nA0VPPLYBD8q1Uj8byvUR5Lp";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);

    $jsonObj = json_decode($response);
    foreach ($jsonObj as $obj)
    {
        if (strpos($obj->type, 'facebook') !== false)
        {
            $team->FacebookURL = $obj->foreign_key;
        } else if (strpos($obj->type, 'twitter') !== false)
        {
            $team->TwitterURL = $obj->foreign_key;
        } else if (strpos($obj->type, 'instagram') !== false)
        {
            $team->InstagramURL = $obj->foreign_key;
        } else if (strpos($obj->type, 'youtube') !== false)
        {
            $team->YoutubeURL = $obj->foreign_key;
        }

        $team->save();
    }

}

?>