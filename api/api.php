<?php
require_once('../config.php');
require_once(ROOT_DIR . '/classes/ChecklistItemResults.php');
require_once(ROOT_DIR . '/classes/ChecklistItems.php');
require_once(ROOT_DIR . '/classes/ScoutCards.php');
require_once(ROOT_DIR . '/classes/PitCards.php');
require_once(ROOT_DIR . '/classes/Teams.php');
require_once(ROOT_DIR . '/classes/Events.php');
require_once(ROOT_DIR . '/classes/RobotMedia.php');
require_once(ROOT_DIR . '/classes/Matches.php');
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
            $response = array();

            $response['ApiKey'] = API_KEY;
            $response['TeamNumber'] = TEAM_NUMBER;
            $response['TeamName'] = TEAM_NAME;

            $api->success($response);

            break;

        case 'GetUsers':
            $api->success(Users::getUsers());

            break;

        case 'GetEvents':
            $api->success(Events::getEvents());

            break;

        case 'GetTeamsAtEvent':

            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

            $event = Events::withId($eventId);

            if (!empty($eventId))
                $api->success($event->getTeams());
            else
                throw new Exception('Invalid event id');

            break;

        case 'GetScoutCards':

            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

            $event = Events::withId($eventId);

            if (!empty($eventId))
                $api->success($event->getScoutCards());
            else
                throw new Exception('Invalid event id');

            break;

        case 'GetRobotMedia':

            $teamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);

            $team = Teams::withId($teamId);

            if (!empty($teamId))
                $api->success($team->getRobotPhotos());
            else
                throw new Exception('Invalid team id');

            break;

        case 'GetPitCards':

            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

            $event = Events::withId($eventId);

            if (!empty($eventId))
                $api->success($event->getPitCards());
            else
                throw new Exception('Invalid event id');

            break;

        case 'GetMatches':
            $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

            $event = Events::withId($eventId);

            if (!empty($event))
                $api->success($event->getMatches());
            else
                throw new Exception('Invalid event id');

            break;

        case 'GetChecklistItems':
            $api->success(ChecklistItems::getChecklistItems());
            break;

        case 'GetChecklistItemResults':

            $checklistItemResults = array();

            foreach(ChecklistItems::getChecklistItems() as $checklistItem)
            {
                $checklistItemResults[] = $checklistItem->getResults();
            }

            $api->success($checklistItemResults);
            break;

        //endregion

        //region Setters
        case 'SubmitScoutCard':
            $scoutCard = ScoutCards::withProperties($_POST);

            if ($scoutCard->save())
                $api->success($scoutCard->Id);
            else
                throw new Exception('Failed to save scout card');

            break;

        case 'SubmitPitCard':
            $pitCard = PitCards::withProperties($_POST);

            if ($pitCard->save())
                $api->success($pitCard->Id);
            else
                throw new Exception('Failed to save pit card');

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
