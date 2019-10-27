<?php
if(php_sapi_name() != 'cli')
    header("HTTP/1.0 401");
else
{
    $bypassCoreCheck = true;
    require_once('../config.php');
    require_once('../classes/tables/core/Teams.php');
    require_once('../classes/tables/core/Events.php');
    require_once('../classes/tables/core/EventTeamList.php');

    set_time_limit(600);

    $pageCount = 17;

    $coreDb->beginTransaction();
    for ($i = 0; $i < $pageCount; $i++)
    {
        $totalPercent = round($i / $pageCount, 2) * 100;

        $url = "https://www.thebluealliance.com/api/v3/teams/$i?X-TBA-Auth-Key=" . $_SESSION[BLUE_ALLIANCE_KEY];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);

        $teams = json_decode($response);
        $teamSize = sizeof($teams);
        for($j = 0; $j < $teamSize; $j++)
        {
            $teamObj = $teams[$j];
            $percent = round($j / $teamSize, 2) * 100;

            if (!empty($teamObj->country))
            {
                $team = new Teams();
                $team->Id = $teamObj->team_number;
                $team->Name = $teamObj->nickname;
                $team->City = $teamObj->city;
                $team->StateProvince = $teamObj->state_prov;
                $team->Country = $teamObj->country;
                $team->RookieYear = $teamObj->rookie_year;
                $team->WebsiteURL = $teamObj->website;
                $team->ImageFileURI = "";

                echo "$i / {$pageCount} - {$totalPercent}% - $j / {$teamSize} - {$percent}% - Saving team {$team->toString()}...\n";

                $team->save($coreDb);
            }
        }
    }
    $coreDb->commit();
}

?>