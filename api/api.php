<?php
require_once('../config.php');
require_once(ROOT_DIR . '/classes/ScoutCards.php');
require_once(ROOT_DIR . '/classes/PitCards.php');
require_once(ROOT_DIR . '/classes/Teams.php');
require_once(ROOT_DIR . '/classes/Events.php');
require_once(ROOT_DIR . '/classes/RobotMedia.php');
require_once(ROOT_DIR . '/classes/Api.php');

$api = new Api($_POST['key']);

$action = $_POST['action'];

switch($action)
{
    //used to establish a connection with the server
    case 'Hello':
        $api->success('Hello Good Sir!');

        break;

    //region Setters
    case 'SubmitScoutCard':
        $scoutCard = new ScoutCards();

        $scoutCard->MatchId = filter_var($_POST['MatchId'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->TeamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->EventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);
        $scoutCard->AllianceColor = filter_var($_POST['AllianceColor'], FILTER_SANITIZE_STRING);
        $scoutCard->CompletedBy = filter_var($_POST['CompletedBy'], FILTER_SANITIZE_STRING);
        $scoutCard->MatchType = filter_var($_POST['MatchType'], FILTER_SANITIZE_STRING);
        $scoutCard->SetNumber = filter_var($_POST['SetNumber'], FILTER_SANITIZE_NUMBER_INT);

        $scoutCard->PreGameStartingLevel = filter_var($_POST['PreGameStartingLevel'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->PreGameStartingPosition = filter_var($_POST['PreGameStartingPosition'], FILTER_SANITIZE_STRING);
        $scoutCard->PreGameStartingPiece = filter_var($_POST['PreGameStartingPiece'], FILTER_SANITIZE_STRING);

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
        $scoutCard->DriveRating = filter_var($_POST['DriveRating'], FILTER_SANITIZE_NUMBER_INT);
        $scoutCard->Notes = filter_var($_POST['Notes'], FILTER_SANITIZE_STRING);
        $scoutCard->CompletedDate = filter_var($_POST['CompletedDate'], FILTER_SANITIZE_STRING);

        if($scoutCard->save())
            $api->success($scoutCard->Id);
        else
            $api->error('Failed to save scout card');

        break;

    case 'SubmitPitCard':
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
            $api->success($pitCard->Id);
        else
            $api->error('Failed to save pit card');

        break;

    case 'SubmitRobotMedia':
        $robotMedia = new RobotMedia();

        $robotMedia->TeamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);
        $robotMedia->Base64Image = $_POST['Base64Image'];

        if($robotMedia->save())
            $api->success($robotMedia->Id);
        else
            $api->error('Failed to save robot media');

        break;
    //endregion

    //region Getters
    case 'GetUsers':
        $api->success(Users::getUsers());

        break;

    case 'GetEvents':
        $api->success(Events::getEvents());

        break;

    case 'GetTeamsAtEvent':

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if(!empty($eventId))
            $api->success(Teams::getTeamsAtEvent($eventId));
        else
            $api->error('Invalid event id');

        break;

    case 'GetScoutCards':

        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if(!empty($eventId))
            $api->success(ScoutCards::getScoutCardsForEvent($eventId));
        else
            $api->error('Invalid event id');

        break;

    case 'GetRobotMedia':
        $teamId = filter_var($_POST['TeamId'], FILTER_SANITIZE_NUMBER_INT);

        if(!empty($teamId))
            $api->success(RobotMedia::getRobotMediaForTeam($teamId));
        else
            $api->error('Invalid team id');

        break;

    case 'GetPitCards':
        $eventId = filter_var($_POST['EventId'], FILTER_SANITIZE_STRING);

        if(!empty($eventId))
            $api->success(PitCards::getPitCardsForEvent($eventId));
        else
            $api->error('Invalid event id');

        break;
    //endregion

    default:
        $api->error('Invalid Action.');
        break;
}
?>
