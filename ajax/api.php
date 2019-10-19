<?php
require_once('../config.php');
require_once('../classes/tables/core/Teams.php');
require_once('../classes/tables/core/EventTeamList.php');
require_once(ROOT_DIR . "/classes/Ajax.php");

$ajax = new Ajax();

switch ($_POST['action'])
{
    case 'opr':
        $url = "https://www.thebluealliance.com/api/v3/event/" . $_POST['eventId'] . "/oprs?X-TBA-Auth-Key=" . BLUE_ALLIANCE_KEY;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);

        $ajax->success(json_decode($response));
        break;
}

?>