<?php
require_once('../config.php');
require_once('../classes/tables/core/Teams.php');
require_once('../classes/tables/core/EventTeamList.php');

$url = "https://www.thebluealliance.com/api/v3/event/" . $_POST['eventId'] . "/oprs?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);

echo $response;

?>