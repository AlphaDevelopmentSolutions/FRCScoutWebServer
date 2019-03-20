<?php
require_once('../config.php');
require_once('../classes/ScoutCards.php');
require_once('../classes/PitCards.php');
require_once('../classes/Teams.php');
require_once('../classes/Events.php');
require_once('../classes/RobotMedia.php');

if($_POST['key'] != API_KEY)
{
    die('Invalid key.');
}

$action = $_POST['action'];

switch($action)
{
    case 'SubmitScoutCard':
        $response = array();
        $scoutCard = new ScoutCards();

        $scoutCard->MatchId = filter_var($_POST['MatchId'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->EventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);
        $scoutCard->AllianceColor = filter_var($_POST['AllianceColor'], FILTER_SANITIZE_STRING);
        $scoutCard->CompletedBy = filter_var($_POST['CompletedBy'], FILTER_SANITIZE_STRING);
        $scoutCard->BlueAllianceFinalScore = filter_var($_POST['BlueAllianceFinalScore'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->RedAllianceFinalScore = filter_var($_POST['RedAllianceFinalScore'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousExitHabitat = filter_var($_POST['AutonomousExitHabitat'], FILTER_SANITIZE_STRING);
        $scoutCard->EndGameReturnedToHabitat = filter_var($_POST['EndGameReturnedToHabitat'], FILTER_SANITIZE_STRING);
        $scoutCard->EndGameReturnedToHabitatAttempts = filter_var($_POST['EndGameReturnedToHabitatAttempts'], FILTER_SANITIZE_STRING);
        $scoutCard->Notes = filter_var($_POST['Notes'], FILTER_SANITIZE_STRING);
        $scoutCard->CompletedDate = filter_var($_POST['CompletedDate'], FILTER_SANITIZE_STRING);

        if($scoutCard->save())
        {
            $response['Status'] = 'Success';
            $response['Response'] = $scoutCard->Id;
        }
        else
        {
            $response['Status'] = 'Error';
            $response['Response'] = 'Failed to save scout card.';
        }

        echo json_encode($response);

        break;

    case 'SubmitMatchItemAction':
        $response = array();
        $matchItemAction = new MatchItemActions();

        $matchItemAction->ScoutCardId = filter_var($_POST['ScoutCardId'], FILTER_SANITIZE_NUMBER_INT);
        $matchItemAction->MatchState = filter_var($_POST['MatchState'], FILTER_SANITIZE_STRING);
        $matchItemAction->ItemType = filter_var($_POST['ItemType'], FILTER_SANITIZE_STRING);
        $matchItemAction->Action = filter_var($_POST['Action'], FILTER_SANITIZE_STRING);

        if($matchItemAction->save())
        {
            $response['Status'] = 'Success';
            $response['Response'] = $matchItemAction->Id;
        }
        else
        {
            $response['Status'] = 'Error';
            $response['Response'] = 'Failed to save match item action.';
        }

        echo json_encode($response);

        break;

    case 'SubmitPitCard':
        $response = array();
        $pitCard = new PitCards();

        $pitCard->TeamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);
        $pitCard->EventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);
        $pitCard->DriveStyle = filter_var($_POST['DriveStyle'], FILTER_SANITIZE_STRING);
        $pitCard->AutoExitHabitat = filter_var($_POST['AutoExitHabitat'], FILTER_SANITIZE_STRING);
        $pitCard->AutoHatch = filter_var($_POST['AutoHatch'], FILTER_SANITIZE_STRING);
        $pitCard->AutoCargo = filter_var($_POST['AutoCargo'], FILTER_SANITIZE_STRING);
        $pitCard->TeleopHatch = filter_var($_POST['TeleopHatch'], FILTER_SANITIZE_STRING);
        $pitCard->TeleopCargo = filter_var($_POST['TeleopCargo'], FILTER_SANITIZE_STRING);
        $pitCard->TeleopRocketsComplete = filter_var($_POST['TeleopRocketsComplete'], FILTER_SANITIZE_STRING);
        $pitCard->ReturnToHabitat = filter_var($_POST['ReturnToHabitat'], FILTER_SANITIZE_STRING);
        $pitCard->Notes = filter_var($_POST['Notes'], FILTER_SANITIZE_STRING);
        $pitCard->CompletedBy = filter_var($_POST['CompletedBy'], FILTER_SANITIZE_STRING);

        if($pitCard->save())
        {
            $response['Status'] = 'Success';
            $response['Response'] = $pitCard->Id;
        }
        else
        {
            $response['Status'] = 'Error';
            $response['Response'] = 'Failed to save pit card.';
        }

        echo json_encode($response);

        break;

    case 'SubmitRobotMedia':
        $response = array();
        $robotMedia = new RobotMedia();

        $robotMedia->TeamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);
        $robotMedia->Base64Image = $_POST['Base64Image'];

        if($robotMedia->save())
        {
            $response['Status'] = 'Success';
            $response['Response'] = $robotMedia->Id;
        }
        else
        {
            $response['Status'] = 'Error';
            $response['Response'] = 'Failed to save robot image.';
        }

        echo json_encode($response);

        break;

    case 'GetTeamsAtEvent':
        $response = array();

        $response['Status'] = 'Success';
        $response['Response'] = Teams::getTeamsAtEvent(filter_var($_POST['EventId'], FILTER_SANITIZE_STRING));

        echo json_encode($response);

        break;

    case 'GetUsers':
        $response = array();

        $response['Status'] = 'Success';
        $response['Response'] = Users::getUsers();

        echo json_encode($response);

        break;

    case 'GetEvents':
        $response = array();

        $response['Status'] = 'Success';
        $response['Response'] = Events::getEvents();

        echo json_encode($response);

        break;

    case 'GetScoutCards':
        $response = array();

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if(!empty($eventId))
        {
            $response['Status'] = 'Success';
            $response['Response'] = ScoutCards::getScoutCardsForEvent($eventId);
        }
        else
        {
            $response['Status'] =  'Error';
            $response['Response'] = 'Invalid event id.';
        }


        echo json_encode($response);

        break;

    case 'GetMatchItemActions':
        $response = array();

        $teamId = filter_var($_POST['ScoutCardId'], FILTER_SANITIZE_NUMBER_INT);

        if(!empty($teamId))
        {
            $response['Status'] = 'Success';
            $response['Response'] = MatchItemActions::getMatchItemActionsForScoutCard($teamId);
        }
        else
        {
            $response['Status'] =  'Error';
            $response['Response'] = 'Invalid scout card id.';
        }


        echo json_encode($response);

        break;

    case 'GetRobotMedia':
        $response = array();

        $teamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);

        if(!empty($teamId))
        {
            $response['Status'] = 'Success';
            $response['Response'] = RobotMedia::getRobotMediaForTeam($teamId);
        }
        else
        {
            $response['Status'] =  'Error';
            $response['Response'] = 'Invalid team id.';
        }


        echo json_encode($response);

        break;

    case 'GetPitCards':
        $response = array();

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if(!empty($eventId))
        {
            $response['Status'] = 'Success';
            $response['Response'] = PitCards::getPitCardsForEvent($eventId);
        }
        else
        {
            $response['Status'] =  'Error';
            $response['Response'] = 'Invalid event id.';
        }


        echo json_encode($response);

        break;
}


?>
