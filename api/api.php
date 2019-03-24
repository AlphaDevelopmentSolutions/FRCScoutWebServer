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
    //region Setters
    case 'SubmitScoutCard':
        $response = array();
        $scoutCard = new ScoutCards();

        $scoutCard->MatchId = filter_var($_POST['MatchId'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->EventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);
        $scoutCard->AllianceColor = filter_var($_POST['AllianceColor'], FILTER_SANITIZE_STRING);
        $scoutCard->CompletedBy = filter_var($_POST['CompletedBy'], FILTER_SANITIZE_STRING);
        
        $scoutCard->AutonomousExitHabitat = filter_var($_POST['AutonomousExitHabitat'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousHatchPanelsPickedUp = filter_var($_POST['AutonomousHatchPanelsPickedUp'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousHatchPanelsSecuredAttempts = filter_var($_POST['AutonomousHatchPanelsSecuredAttempts'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousHatchPanelsSecured = filter_var($_POST['AutonomousHatchPanelsSecured'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousCargoPickedUp = filter_var($_POST['AutonomousCargoPickedUp'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousCargoStoredAttempts = filter_var($_POST['AutonomousCargoStoredAttempts'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->AutonomousCargoStored = filter_var($_POST['AutonomousCargoStored'], FILTER_SANITIZE_NUMBER_INT);

        $scoutCard->TeleopHatchPanelsPickedUp = filter_var($_POST['TeleopHatchPanelsPickedUp'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeleopHatchPanelsSecuredAttempts = filter_var($_POST['TeleopHatchPanelsSecuredAttempts'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeleopHatchPanelsSecured = filter_var($_POST['TeleopHatchPanelsSecured'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeleopCargoPickedUp = filter_var($_POST['TeleopCargoPickedUp'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeleopCargoStoredAttempts = filter_var($_POST['TeleopCargoStoredAttempts'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeleopCargoStored = filter_var($_POST['TeleopCargoStored'], FILTER_SANITIZE_NUMBER_INT);
        
        $scoutCard->EndGameReturnedToHabitat = filter_var($_POST['EndGameReturnedToHabitat'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->EndGameReturnedToHabitatAttempts = filter_var($_POST['EndGameReturnedToHabitatAttempts'], FILTER_SANITIZE_NUMBER_INT);

        $scoutCard->BlueAllianceFinalScore = filter_var($_POST['BlueAllianceFinalScore'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->RedAllianceFinalScore = filter_var($_POST['RedAllianceFinalScore'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->DefenseRating = filter_var($_POST['DefenseRating'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->OffenseRating = filter_var($_POST['OffenseRating'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->DriveRating = filter_var($_POST['DriveRatDriveRatinging'], FILTER_SANITIZE_NUMBER_INT);
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

    case 'SubmitPitCard':
        $response = array();
        $pitCard = new PitCards();

        $pitCard->TeamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);
        $pitCard->EventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        $pitCard->DriveStyle = filter_var($_POST['DriveStyle'], FILTER_SANITIZE_STRING);
        $pitCard->RobotWeight = filter_var($_POST['RobotWeight'], FILTER_SANITIZE_STRING);
        $pitCard->RobotLength = filter_var($_POST['RobotLength'], FILTER_SANITIZE_STRING);
        $pitCard->RobotWidth = filter_var($_POST['RobotWidth'], FILTER_SANITIZE_STRING);
        $pitCard->RobotHeight = filter_var($_POST['RobotHeight'], FILTER_SANITIZE_STRING);

        $pitCard->AutoExitHabitat = filter_var($_POST['AutoExitHabitat'], FILTER_SANITIZE_STRING);
        $pitCard->AutoHatch = filter_var($_POST['AutoHatch'], FILTER_SANITIZE_STRING);
        $pitCard->AutoCargo = filter_var($_POST['AutoCargo'], FILTER_SANITIZE_STRING);

        $pitCard->TeleopHatch = filter_var($_POST['TeleopHatch'], FILTER_SANITIZE_STRING);
        $pitCard->TeleopCargo = filter_var($_POST['TeleopCargo'], FILTER_SANITIZE_STRING);

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
    //endregion

    //region Getters
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

    case 'GetTeamsAtEvent':
        $response = array();

        $response['Status'] = 'Success';
        $response['Response'] = Teams::getTeamsAtEvent(filter_var($_POST['EventId'], FILTER_SANITIZE_STRING));

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
    //endregion


}


?>
