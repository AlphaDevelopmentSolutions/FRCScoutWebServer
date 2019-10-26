<?php
$bypassCoreCheck = true;
require_once('../../config.php');
require_once(ROOT_DIR . '/classes/Api.php');

$api = new Api();

$account = Accounts::withApiKey($coreDb, $_POST[$api->API_KEY]);

if(empty($account->Username))
   $api->forbidden("You must be logged in to access this page");

setCoreAccount($account);
$localDb = new LocalDatabase();

switch($_POST[$api->ACTION_KEY])
{

    case 'GetServerConfig':

        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/local/Config.php');

        $configs = Config::getObjects($localDb);

        $team = Teams::withId($coreDb, $account->TeamId);

        $obj = new Config();
        $obj->Key = "TEAM_NUMBER";
        $obj->Value = $team->Id;
        $configs[] = $obj;

        $obj = new Config();
        $obj->Key = "TEAM_NAME";
        $obj->Value = $team->Name;
        $configs[] = $obj;

        $obj = new Config();
        $obj->Key = "ROBOT_MEDIA_DIR";
        $obj->Value = $account->RobotMediaDir;
        $configs[] = $obj;

        $obj = new Config();
        $obj->Key = "API_KEY";
        $obj->Value = $account->ApiKey;
        $configs[] = $obj;

        $api->success($configs);

        break;

    case 'GetUsers':
        $api->success(Users::getObjects($localDb));

        break;

    case 'GetEvents':

        require_once(ROOT_DIR . '/classes/tables/core/Events.php');

        $teamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($teamId))
        {
            $team = Teams::withId($coreDb, $teamId);
            $api->success(Events::getObjects($coreDb, null, $team));
        } else
            $api->success(Events::getObjects($coreDb));

        break;

    case 'GetEventTeamList':

        require_once(ROOT_DIR . '/classes/tables/core/EventTeamList.php');

        $api->success(EventTeamList::getObjects($coreDb));
        break;

    case 'GetYears':

        require_once(ROOT_DIR . '/classes/tables/core/Years.php');

        $api->success(Years::getObjects($coreDb));
        break;

    case 'GetTeams':

        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if (!empty($eventId))
        {
            $event = Events::withId($coreDb, $eventId);
            $api->success(Teams::getObjects($coreDb, $event));
        } else
            $api->success(Teams::getObjects($coreDb));

        break;

    case 'GetScoutCardInfo':

        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if (!empty($eventId))
        {
            $event = Events::withId($coreDb, $eventId);
            $api->success(ScoutCardInfo::getObjects($localDb, null, null, $event));
        } else
            $api->success(ScoutCardInfo::getObjects($localDb));

        break;

    case 'GetScoutCardInfoKeys':

        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfoKeys.php');

        $yearId = filter_var($_POST['YearId'], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($yearId))
        {
            $year = Years::withId($coreDb, $yearId);
            $api->success(ScoutCardInfoKeys::getObjects($localDb, $year));
        } else
            $api->success(ScoutCardInfoKeys::getObjects($localDb));

        break;

    case 'GetRobotMedia':

        require_once(ROOT_DIR . '/classes/tables/local/RobotMedia.php');

        $teamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($teamId))
        {
            $team = Teams::withId($coreDb, $teamId);
            $api->success(RobotMedia::getObjects($localDb, null, null, $team));
        } else
            $api->success(RobotMedia::getObjects($localDb));

        break;

    case 'GetRobotInfo':

        require_once(ROOT_DIR . '/classes/tables/local/RobotInfo.php');

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if (!empty($eventId))
        {
            $event = Events::withId($coreDb, $eventId);
            $api->success(RobotInfo::getObjects($localDb, null, null, $event));
        } else
            $api->success(RobotInfo::getObjects($localDb));

        break;

    case 'GetRobotInfoKeys':

        require_once(ROOT_DIR . '/classes/tables/local/RobotInfoKeys.php');

        $yearId = filter_var($_POST['YearId'], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($eventId) || !empty($yearId))
        {
            $year = (!empty($yearId)) ? Years::withId($coreDb, $yearId) : null;

            $api->success(RobotInfoKeys::getKeys($localDb, $year));
        } else
            $api->success(RobotInfoKeys::getObjects($localDb));

        break;

    case 'GetMatches':

        require_once(ROOT_DIR . '/classes/tables/core/Matches.php');

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if (!empty($event))
        {
            $event = Events::withId($coreDb, $eventId);
            $api->success(Matches::getObjects($coreDb, $event));

        } else
            $api->success(Matches::getObjects($coreDb));

        break;

    case 'GetChecklistItems':

        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItems.php');

        $api->success(ChecklistItems::getObjects($localDb));

        break;

    case 'GetChecklistItemResults':

        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItemResults.php');

        $api->success(ChecklistItemResults::getObjects($localDb));

        break;

    default:
        $api->notImplemented("Action not found.");
        break;
}

?>
