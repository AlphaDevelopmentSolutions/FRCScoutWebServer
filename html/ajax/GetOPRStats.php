<?php
require_once('../config.php');
require_once('../classes/Teams.php');
require_once('../classes/EventTeamList.php');

$url = "https://www.thebluealliance.com/api/v3/event/" . $_POST['eventCode'] . "/oprs?X-TBA-Auth-Key=gGDqr1h7gbcdKAumaFgnuzPJYDox7vz6gyX1a8r9nA0VPPLYBD8q1Uj8byvUR5Lp";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);

echo $response;

?>