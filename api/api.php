<?php
$action = $_POST['action'];

if($action != 'Hello')
{
    //core account must be logged in to use the API
    session_start();
    if (empty($_SESSION['coreAccount']))
    {
        define('ROOT_DIR', dirname('../config.php'));
        require_once(ROOT_DIR . '/classes/Keys.php');
        require_once(ROOT_DIR . '/classes/Database.php');
        require_once(ROOT_DIR . '/classes/tables/Table.php');
        require_once(ROOT_DIR . '/classes/tables/core/Accounts.php');
        require_once(ROOT_DIR . '/classes/Api.php');

        //attempt to login to the core account
        $coreAccount = Accounts::login($_POST['CoreUsername'], $_POST['CorePassword']);

        if (!empty($coreAccount))
            $_SESSION['coreAccount'] = serialize($coreAccount);
        else
        {
            //invalid core, kill the script
            $api = new Api($_POST['key']);
            $api->error('Invalid core account.');
            die();
        }
    }
}

require_once('../config.php');
require_once(ROOT_DIR . '/classes/tables/local/ChecklistItemResults.php');
require_once(ROOT_DIR . '/classes/tables/local/ChecklistItems.php');
require_once(ROOT_DIR . '/classes/tables/core/Years.php');
require_once(ROOT_DIR . '/classes/tables/core/EventTeamList.php');
require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfoKeys.php');
require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');
require_once(ROOT_DIR . '/classes/tables/local/RobotInfo.php');
require_once(ROOT_DIR . '/classes/tables/local/RobotInfoKeys.php');
require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
require_once(ROOT_DIR . '/classes/tables/core/Events.php');
require_once(ROOT_DIR . '/classes/tables/local/RobotMedia.php');
require_once(ROOT_DIR . '/classes/tables/core/Matches.php');
require_once(ROOT_DIR . '/classes/Api.php');

$api = new Api($_POST['key']);

//check if the key was valid
if(!$api->getKeyValid())
{
    //only the hello api and server config api bypass the key
    if($action != 'Hello' && $action != 'GetServerConfig')
    {
        $api->error('Invalid Key');
        die();
    }
}

try {

    switch ($action)
    {
        //used to establish a connection with the server
        case 'Hello':
            $api->success('Hello Good Sir!');

            break;

        //region Getters
        case 'GetServerConfig':

            $configs = Config::getObjects();

            $team = Teams::withId(getCoreAccount()->TeamId);

            $teamNumber = new Config();
            $teamNumber->Key = "TEAM_NUMBER";
            $teamNumber->Value = $team->Id;

            $teamName = new Config();
            $teamName->Key = "TEAM_NAME";
            $teamName->Value = $team->Name;


            $configs[] = $teamNumber;
            $configs[] = $teamName;

            $api->success($configs);

            break;

        case 'GetUsers':
            $api->success(Users::getObjects());

            break;

        case 'GetEvents':
            $teamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($teamId))
            {
                $team = Teams::withId($teamId);
                $api->success($team->getEvents());
            }
            else
                $api->success(Events::getObjects());

            break;

        case 'GetEventTeamList':
            $api->success(EventTeamList::getObjects());

        case 'GetYears':
            $api->success(Years::getObjects());
            break;

        case 'GetTeams':
            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

            if (!empty($eventId))
            {
                $event = Events::withId($eventId);
                $api->success($event->getTeams());
            }
            else
                $api->success(Teams::getObjects());

            break;

        case 'GetScoutCardInfo':
            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

            if (!empty($eventId))
            {
                $event = Events::withId($eventId);
                $api->success($event->getScoutCardInfo());
            }
            else
                $api->success(ScoutCardInfo::getObjects());

            break;

        case 'GetScoutCardInfoKeys':
            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);
            $yearId = filter_var($_POST['YearId'], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($eventId) || !empty($yearId))
            {
                $event = (!empty($eventId)) ? Events::withId($eventId) : null;
                $year = (!empty($yearId)) ? Years::withId($yearId) : null;

                $api->success(ScoutCardInfoKeys::getKeys($year, $event));
            }
            else
                $api->success(ScoutCardInfoKeys::getObjects());

            break;

        case 'GetRobotMedia':

            $teamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($teamId))
            {
                $team = Teams::withId($teamId);

                $api->success($team->getRobotPhotos());
            }

            else
                $api->success(RobotMedia::getObjects());

            break;

        case 'GetRobotInfo':
            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

            if (!empty($eventId))
            {
                $event = Events::withId($eventId);

                $api->success($event->getRobotInfo());
            }
            else
                $api->success(RobotInfo::getObjects());

            break;

        case 'GetRobotInfoKeys':
            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);
            $yearId = filter_var($_POST['YearId'], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($eventId) || !empty($yearId))
            {
                $event = (!empty($eventId)) ? Events::withId($eventId) : null;
                $year = (!empty($yearId)) ? Years::withId($yearId) : null;

                $api->success(RobotInfoKeys::getKeys($year, $event));
            }
            else
                $api->success(RobotInfoKeys::getObjects());

            break;

        case 'GetMatches':
            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

            if (!empty($event))
            {
                $event = Events::withId($eventId);

                $api->success($event->getMatches());

            }
            else
                $api->success(Matches::getObjects());

            break;

        case 'GetChecklistItems':
            $api->success(ChecklistItems::getObjects());

            break;

        case 'GetChecklistItemResults':
            $api->success(ChecklistItemResults::getObjects());

            break;

        //endregion

        //region Setters
        case 'SubmitScoutCardInfo':
            $scoutCardInfo = ScoutCardInfo::withProperties($_POST);

            if ($scoutCardInfo->save())
                $api->success($scoutCardInfo->Id);
            else
                throw new Exception('Failed to save scout card info');

            break;

        case 'SubmitRobotInfo':
            $robotInfo = RobotInfo::withProperties($_POST);

            if ($robotInfo->save())
                $api->success($robotInfo->Id);
            else
                throw new Exception('Failed to save robot info');

            break;

        case 'SubmitRobotMedia':
            $robotMedia = RobotMedia::withProperties($_POST);

            if ($robotMedia->save())
                $api->success($robotMedia->Id);
            else
                throw new Exception('Failed to save robot media');

            break;

        case 'SubmitChecklistItemResult':
            $checklistItemResult = ChecklistItemResults::withProperties($_POST);

            if ($checklistItemResult->save())
                $api->success($checklistItemResult->Id);
            else
                throw new Exception('Failed to save checklist item result');

            break;
        //endregion

        default:
            throw new Exception('Invalid action');
            break;
    }
}
catch (Exception $e)
{
    $api->error($e->getMessage());
}
?>
