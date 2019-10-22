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

    case 'SubmitScoutCardInfo':

        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');

        $scoutCardInfo = ScoutCardInfo::withProperties($_POST);

        if ($scoutCardInfo->save())
            $api->success($scoutCardInfo->Id);
        else
            $api->error('Failed to save scout card info');

        break;

    case 'SubmitRobotInfo':

        require_once(ROOT_DIR . '/classes/tables/local/RobotInfo.php');

        $robotInfo = RobotInfo::withProperties($_POST);

        if ($robotInfo->save())
            $api->success($robotInfo->Id);
        else
            $api->error('Failed to save robot info');

        break;

    case 'SubmitRobotMedia':

        require_once(ROOT_DIR . '/classes/tables/local/RobotMedia.php');

        $robotMedia = RobotMedia::withProperties($_POST);

        if ($robotMedia->save())
            $api->success($robotMedia->Id);
        else
            $api->error('Failed to save robot media');

        break;

    case 'SubmitChecklistItemResult':

        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItemResults.php');

        $checklistItemResult = ChecklistItemResults::withProperties($_POST);

        if ($checklistItemResult->save())
            $api->success($checklistItemResult->Id);
        else
            $api->error('Failed to save checklist item result');

        break;

    default:
        $api->notImplemented("Action not found.");
        break;
}

?>
