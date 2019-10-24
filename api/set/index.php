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

    case 'SubmitScoutCardInfo':

        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');

        $scoutCardInfo = ScoutCardInfo::withProperties($_POST);

        if ($scoutCardInfo->save($localDb, $coreDb))
            $api->success($scoutCardInfo->Id);
        else
            $api->error('Failed to save scout card info');

        break;

    case 'SubmitRobotInfo':

        require_once(ROOT_DIR . '/classes/tables/local/RobotInfo.php');

        $robotInfo = RobotInfo::withProperties($_POST);

        if ($robotInfo->save($localDb, $coreDb))
            $api->success($robotInfo->Id);
        else
            $api->error('Failed to save robot info');

        break;

    case 'SubmitRobotMedia':

        require_once(ROOT_DIR . '/classes/tables/local/RobotMedia.php');

        $robotMedia = RobotMedia::withProperties($_POST);

        if ($robotMedia->save($localDb))
            $api->success($robotMedia->Id);
        else
            $api->error('Failed to save robot media');

        break;

    case 'SubmitChecklistItemResult':

        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItemResults.php');

        $checklistItemResult = ChecklistItemResults::withProperties($_POST);

        if ($checklistItemResult->save($localDb, $coreDb))
            $api->success($checklistItemResult->Id);
        else
            $api->error('Failed to save checklist item result');

        break;

    default:
        $api->notImplemented("Action not found.");
        break;
}

?>
