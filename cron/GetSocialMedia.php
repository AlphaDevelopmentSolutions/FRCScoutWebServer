<?php
if(php_sapi_name() != 'cli')
    header("HTTP/1.0 401");
else
{
    $bypassCoreCheck = true;
    require_once('../config.php');
    require_once('../classes/tables/core/Teams.php');

    set_time_limit(600);

    $teams = Teams::getObjects($coreDb);
    $teamSize = sizeof($teams);
    for($i = 0; $i < $teamSize; $i++)
    {
        $team = $teams[$i];
        $percent = round($i / $teamSize, 2) * 100;

        echo "$i / {$teamSize} - {$percent}% - Getting social media for team {$team->toString()} ...\n";

        $url = "https://www.thebluealliance.com/api/v3/team/frc" . $team->Id . "/social_media?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        $jsonObj = json_decode($response);
        if(sizeof($jsonObj) > 0)
        {
            $facebookUrl = null;
            $twitterUrl = null;
            $instagramUrl = null;
            $youtubeUrl = null;

            foreach ($jsonObj as $obj) {
                if (strpos($obj->type, 'facebook') !== false) {
                    $facebookUrl = $obj->foreign_key;

                } else if (strpos($obj->type, 'twitter') !== false) {
                    $twitterUrl = $obj->foreign_key;

                } else if (strpos($obj->type, 'instagram') !== false) {
                    $instagramUrl = $obj->foreign_key;

                } else if (strpos($obj->type, 'youtube') !== false) {
                    $youtubeUrl = $obj->foreign_key;
                }
            }

            $team->FacebookURL = $facebookUrl;
            $team->TwitterURL = $twitterUrl;
            $team->InstagramURL = $instagramUrl;
            $team->YoutubeURL = $youtubeUrl;

            $team->save($coreDb);
        }
    }
}

?>