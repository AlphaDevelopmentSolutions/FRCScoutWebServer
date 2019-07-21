<?php
require_once('../config.php');
require_once(ROOT_DIR . '/classes/tables/ChecklistItemResults.php');
require_once(ROOT_DIR . '/classes/tables/ChecklistItems.php');
require_once(ROOT_DIR . '/classes/tables/Years.php');
require_once(ROOT_DIR . '/classes/tables/EventTeamList.php');
require_once(ROOT_DIR . '/classes/tables/ScoutCardInfoKeys.php');
require_once(ROOT_DIR . '/classes/tables/ScoutCardInfo.php');
require_once(ROOT_DIR . '/classes/tables/RobotInfo.php');
require_once(ROOT_DIR . '/classes/tables/RobotInfoKeys.php');
require_once(ROOT_DIR . '/classes/tables/Teams.php');
require_once(ROOT_DIR . '/classes/tables/Events.php');
require_once(ROOT_DIR . '/classes/tables/RobotMedia.php');
require_once(ROOT_DIR . '/classes/tables/Matches.php');
require_once(ROOT_DIR . '/classes/Api.php');

$api = new Api($_POST['key']);

$action = $_POST['action'];

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

            $api->success(array(
                'ApiKey' => API_KEY,
                'TeamNumber' => TEAM_NUMBER,
                'TeamName' => TEAM_NAME
            ));

            break;

        case 'GetUsers':
            $api->success(Users::getObjects());

            break;

        case 'GetEvents':
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
