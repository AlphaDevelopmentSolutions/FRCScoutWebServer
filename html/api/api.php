<?php
require_once('../config.php');
require_once('../classes/ScoutCards.php');
require_once('../classes/Teams.php');
require_once('../classes/Users.php');
require_once('../classes/Events.php');

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
        $scoutCard->CompletedBy = filter_var($_POST['CompletedBy'], FILTER_SANITIZE_STRING);
        $scoutCard->BlueAllianceFinalScore = filter_var($_POST['BlueAllianceFinalScore'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->RedAllianceFinalScore = filter_var($_POST['RedAllianceFinalScore'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousExitHabitat = filter_var($_POST['AutonomousExitHabitat'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousHatchPanelsSecured = filter_var($_POST['AutonomousHatchPanelsSecured'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousCargoStored = filter_var($_POST['AutonomousCargoStored'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeleopHatchPanelsSecured = filter_var($_POST['TeleopHatchPanelsSecured'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeleopCargoStored = filter_var($_POST['TeleopCargoStored'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeleopRocketsCompleted = filter_var($_POST['TeleopRocketsCompleted'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->EndGameReturnedToHabitat = filter_var($_POST['EndGameReturnedToHabitat'], FILTER_SANITIZE_STRING);
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
}


?>
