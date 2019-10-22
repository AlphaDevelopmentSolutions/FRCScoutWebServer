<?php
$bypassCoreCheck = true;
require_once('../../config.php');
require_once(ROOT_DIR . '/classes/Api.php');

$api = new Api();

$account = Accounts::withApiKey($_POST[$api->API_KEY]);

if(empty($account->Username))
   $api->forbidden("You must be logged in to access this page");

setCoreAccount($account);

switch($_POST[$api->ACTION_KEY])
{

    case 'GetServerConfig':

        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/local/Config.php');

        $configs = Config::getObjects();

        $team = Teams::withId($account->TeamId);

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
        $api->success(Users::getObjects());

        break;

    case 'GetEvents':

        require_once(ROOT_DIR . '/classes/tables/core/Events.php');

        $teamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($teamId))
        {
            $team = Teams::withId($teamId);
            $api->success($team->getEvents());
        } else
            $api->success(Events::getObjects());

        break;

    case 'GetEventTeamList':

        require_once(ROOT_DIR . '/classes/tables/core/EventTeamList.php');

        $api->success(EventTeamList::getObjects());

    case 'GetYears':

        require_once(ROOT_DIR . '/classes/tables/core/Years.php');

        $api->success(Years::getObjects());
        break;

    case 'GetTeams':

        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if (!empty($eventId))
        {
            $event = Events::withId($eventId);
            $api->success($event->getTeams());
        } else
            $api->success(Teams::getObjects());

        break;

    case 'GetScoutCardInfo':

        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if (!empty($eventId))
        {
            $event = Events::withId($eventId);
            $api->success(ScoutCardInfo::getObjects(null, null, $event));
        } else
            $api->success(ScoutCardInfo::getObjects());

        break;

    case 'GetScoutCardInfoKeys':

        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfoKeys.php');

        $yearId = filter_var($_POST['YearId'], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($yearId))
        {
            $year = Years::withId($yearId);

            $api->success(ScoutCardInfoKeys::getObjects($year));
        } else
            $api->success(ScoutCardInfoKeys::getObjects());

        break;

    case 'GetRobotMedia':

        require_once(ROOT_DIR . '/classes/tables/local/RobotMedia.php');

        $teamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($teamId))
        {
            $team = Teams::withId($teamId);

            $api->success(RobotMedia::getObjects(null, null, $team));
        } else
            $api->success(RobotMedia::getObjects());

        break;

    case 'GetRobotInfo':

        require_once(ROOT_DIR . '/classes/tables/local/RobotInfo.php');

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if (!empty($eventId))
        {
            $event = Events::withId($eventId);

            $api->success(RobotInfo::getObjects(null, null, $event));
        } else
            $api->success(RobotInfo::getObjects());

        break;

    case 'GetRobotInfoKeys':

        require_once(ROOT_DIR . '/classes/tables/local/RobotInfoKeys.php');

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);
        $yearId = filter_var($_POST['YearId'], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($eventId) || !empty($yearId))
        {
            $event = (!empty($eventId)) ? Events::withId($eventId) : null;
            $year = (!empty($yearId)) ? Years::withId($yearId) : null;

            $api->success(RobotInfoKeys::getKeys($year, $event));
        } else
            $api->success(RobotInfoKeys::getObjects());

        break;

    case 'GetMatches':

        require_once(ROOT_DIR . '/classes/tables/core/Matches.php');

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if (!empty($event))
        {
            $event = Events::withId($eventId);

            $api->success(Matches::getObjects($event));

        } else
            $api->success(Matches::getObjects());

        break;

    case 'GetChecklistItems':

        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItems.php');

        $api->success(ChecklistItems::getObjects());

        break;

    case 'GetChecklistItemResults':

        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItemResults.php');

        $api->success(ChecklistItemResults::getObjects());

        break;

    default:
        $api->notImplemented("Action not found.");
        break;
}

?>
