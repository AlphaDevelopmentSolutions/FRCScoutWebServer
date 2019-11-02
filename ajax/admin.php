<?php
require_once("../config.php");
require_once(ROOT_DIR . "/classes/Ajax.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Matches.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItems.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfo.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfo.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItemResults.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotMedia.php");

$ajax = new Ajax();

if(!getUser()->IsAdmin)
    $ajax->error('You must be logged in as an administrator to access this page.');

$class = $_POST['class'];
unset($_POST['class']);

switch ($_POST['action'])
{
    case 'save':

        unset($_POST['action']);

        switch($class)
        {

            //region Non Info Classes
            case RobotInfoKeys::class:

                $robotInfoKey = RobotInfoKeys::withProperties($_POST['data']);

                if(empty($robotInfoKey->YearId))
                    $ajax->error("Year cannot be empty.");

                if(!ctype_digit($robotInfoKey->YearId))
                    $ajax->error("Year may only be numeric (0-9).");

                if(empty($robotInfoKey->KeyState))
                    $ajax->error("Game state cannot be empty.");

                if(empty($robotInfoKey->KeyName))
                    $ajax->error("Info name cannot be empty.");

                if(empty($robotInfoKey->SortOrder))
                    $ajax->error("Sort order cannot be empty.");

                if(!ctype_digit($robotInfoKey->SortOrder))
                    $ajax->error("Sort order may only be numeric (0-9).");

                if($robotInfoKey->save($localDb))
                    $ajax->success("Robot info saved successfully.");

                else
                    $ajax->error("Robot info failed to save.");

                break;

            case ScoutCardInfoKeys::class:

                $scoutCardInfoKey = ScoutCardInfoKeys::withProperties($_POST['data']);

                if(empty($scoutCardInfoKey->YearId))
                    $ajax->error("Year cannot be empty.");

                if(!ctype_digit($scoutCardInfoKey->YearId))
                    $ajax->error("Year may only be numeric (0-9).");

                if(empty($scoutCardInfoKey->KeyState))
                    $ajax->error("Game state cannot be empty.");

                if(empty($scoutCardInfoKey->KeyName))
                    $ajax->error("Info name cannot be empty.");

                if(empty($scoutCardInfoKey->SortOrder))
                    $ajax->error("Sort order cannot be empty.");

                if(!ctype_digit($scoutCardInfoKey->SortOrder))
                    $ajax->error("Sort order may only be numeric (0-9).");

                if(!ctype_digit($scoutCardInfoKey->MinValue) && $scoutCardInfoKey->MinValue != "")
                    $ajax->error("Min value may only be numeric (0-9).");

                if(!ctype_digit($scoutCardInfoKey->MaxValue)  && $scoutCardInfoKey->MaxValue != "")
                    $ajax->error("Max value may only be numeric (0-9).");

                if(!ctype_digit($scoutCardInfoKey->NullZeros))
                    $ajax->error("Nullify zeros may only be 1 (Yes) or 0 (No).");

                if($scoutCardInfoKey->NullZeros != 0 && $scoutCardInfoKey->NullZeros != 1)
                    $ajax->error("Nullify zeros may only be 1 (Yes) or 0 (No).");

                if(!ctype_digit($scoutCardInfoKey->IncludeInStats))
                    $ajax->error("Include in stats may only be 1 (Yes) or 0 (No).");

                if($scoutCardInfoKey->IncludeInStats != 0 && $scoutCardInfoKey->IncludeInStats != 1)
                    $ajax->error("Include in stats may only be 1 (Yes) or 0 (No).");

                if(!ctype_alpha($scoutCardInfoKey->DataType))
                    $ajax->error("Datatype may only be alphanumeric (A-Z 0-9).");

                if(!in_array($scoutCardInfoKey->DataType, DataTypes::DATA_TYPES))
                    $ajax->error("Invalid datatype.");

                if($scoutCardInfoKey->Id > 0)
                    if($scoutCardInfoKey->DataType != ScoutCardInfoKeys::withId($localDb, $scoutCardInfoKey->Id)->DataType && count(ScoutCardInfo::getObjects($localDb, $scoutCardInfoKey)) > 0)
                        $ajax->error("You can't change datatypes if you already have scout cards populated under this key.");

                $scoutCardInfoKey->NullZeros = $scoutCardInfoKey->NullZeros == 1 ? true : false;
                $scoutCardInfoKey->IncludeInStats = $scoutCardInfoKey->IncludeInStats == 1 ? true : false;

                if($scoutCardInfoKey->save($localDb))
                    $ajax->success("Scout card info saved successfully.");

                else
                    $ajax->error("Scout card info failed to save.");

                break;

            case ChecklistItems::class:

                $checklistItem = ChecklistItems::withProperties($_POST['data']);

                if(empty($checklistItem->YearId))
                    $ajax->error("Year cannot be empty.");

                if(!ctype_digit($checklistItem->YearId))
                    $ajax->error("Year may only be numeric (0-9).");

                if(empty($checklistItem->Title))
                    $ajax->error("Title cannot be empty.");

                if(empty($checklistItem->Description))
                    $ajax->error("Description cannot be empty.");

                if($checklistItem->save($localDb))
                    $ajax->success("Checklist info saved successfully.");

                else
                    $ajax->error("Checklist info failed to save.");

                break;

            case Users::class:

                $user = Users::withProperties($_POST['data']);

                if(empty($user->FirstName))
                    $ajax->error("First name cannot be empty.");

                if(empty($user->LastName))
                    $ajax->error("Last name cannot be empty.");

                if(!empty($user->IsAdmin) && $user->IsAdmin != 0 && $user->IsAdmin != 1)
                    $ajax->error("Admin flag may only be 1 (Yes) or 0 (No).");

                if($user->IsAdmin == 1)
                {
                    if(empty($user->UserName))
                        $ajax->error("Username may not be empty.");

                    if(!validAlnum($user->UserName))
                        $ajax->error("Username may only be alphanumeric (A-Z 0-9).");

                    //Password is new or empty,
                    if(strpos($user->Password, "•") === false)
                    {
                        if(empty($user->Password))
                            $ajax->error("Password may not be empty.");

                        if(!validAlnum($user->Password))
                            $ajax->error("Password may only be alphanumeric (A-Z 0-9).");

                        $user->Password = password_hash($user->Password, PASSWORD_ARGON2ID);
                    }

                    //Password still has the default dots, set from previous hashed password
                    else
                        $user->Password = Users::withId($localDb, $user->Id)->Password;
                }
                else
                {
                    $user->UserName = null;
                    $user->Password = null;
                }

                $user->IsAdmin = $user->IsAdmin == 1 ? true : false;

                if($user->save($localDb))
                    $ajax->success("User saved successfully.");

                else
                    $ajax->error("User failed to save.");

                break;

            case Config::class:

                $configs = Config::getObjects($localDb);

                $data = $_POST['data'];

                $coreAccount = getCoreAccount();

                $success = true;

                foreach ($configs as $config)
                {
                    switch ($config->Key)
                    {
                        case "APP_NAME":
                            $config->Value = $data['AppName'];
                            break;

                        case "PRIMARY_COLOR":
                            $config->Value = $data['PrimaryColor'];
                            break;

                        case "PRIMARY_COLOR_DARK":
                            $config->Value = $data['PrimaryColorDark'];
                            break;
                    }

                    if (empty($config->Value))
                        $ajax->error("Value name cannot be empty.");

                    if (!$config->save($localDb))
                        $success = false;
                }

                if(!$success)
                    $ajax->error("Error saving config. Please try again.");

                $ajax->success("Config saved successfully.");

                break;

            //endregion

            //region Info Classes

            case RobotInfo::class:

                $robotInfo = RobotInfo::withProperties($_POST['data']);

                if(empty($robotInfo->YearId))
                    $ajax->error("Year cannot be empty.");

                if(!ctype_digit($robotInfo->YearId))
                    $ajax->error("Year may only be numeric (0-9).");

                if(empty($robotInfo->EventId))
                    $ajax->error("Event id cannot be empty.");

                if(!ctype_alnum($robotInfo->EventId))
                    $ajax->error("Event id may only be alpha-numeric (A-Z 0-9).");

                if(empty($robotInfo->TeamId))
                    $ajax->error("Team id cannot be empty.");

                if(!ctype_digit($robotInfo->TeamId))
                    $ajax->error("Team id may only be numeric (0-9).");

                if(empty($robotInfo->PropertyKeyId))
                    $ajax->error("Property key id cannot be empty.");

                if(!ctype_digit($robotInfo->PropertyKeyId))
                    $ajax->error("Property key id may only be numeric (0-9).");

                if($robotInfo->save($localDb, $coreDb))
                    $ajax->success("Robot info saved successfully.");

                else
                    $ajax->error("Robot info failed to save.");

                break;

            case ScoutCardInfo::class:

                $scoutCardInfo = ScoutCardInfo::withProperties($_POST['data']);

                if(empty($scoutCardInfo->YearId))
                    $ajax->error("Year cannot be empty.");

                if(!ctype_digit($scoutCardInfo->YearId))
                    $ajax->error("Year may only be numeric (0-9).");

                if(empty($scoutCardInfo->EventId))
                    $ajax->error("Event id cannot be empty.");

                if(!ctype_alnum($scoutCardInfo->EventId))
                    $ajax->error("Event id may only be alpha-numeric (A-Z 0-9).");

                if(empty($scoutCardInfo->MatchId))
                    $ajax->error("Match id cannot be empty.");

                if(!ctype_alnum(str_replace("_", "", $scoutCardInfo->MatchId)))
                    $ajax->error("Match id may only be alpha-numeric (A-Z 0-9).");

                if(empty($scoutCardInfo->TeamId))
                    $ajax->error("Team id cannot be empty.");

                if(!ctype_digit($scoutCardInfo->TeamId))
                    $ajax->error("Team id may only be numeric (0-9).");

                if(empty($scoutCardInfo->PropertyKeyId))
                    $ajax->error("Property key id cannot be empty.");

                if(!ctype_digit($scoutCardInfo->PropertyKeyId))
                    $ajax->error("Property key id may only be numeric (0-9).");

                if($scoutCardInfo->save($localDb, $coreDb))
                    $ajax->success("Scout card saved successfully.");

                else
                    $ajax->error("Scout card failed to save.");

                break;

            case ChecklistItemResults::class:

                $checklistItemResult = ChecklistItemResults::withProperties($_POST['data']);

                if(empty($checklistItemResult->ChecklistItemId))
                    $ajax->error("Checklist item id cannot be empty.");

                if(!ctype_digit($checklistItemResult->ChecklistItemId))
                    $ajax->error("Checklist item id may only be numeric (0-9).");

                if(empty($checklistItemResult->MatchId))
                    $ajax->error("Match id cannot be empty.");

                if(!ctype_alnum(str_replace("_", "", $checklistItemResult->MatchId)))
                    $ajax->error("Match id may only be alpha-numeric (A-Z 0-9).");

                if(empty($checklistItemResult->Status))
                    $ajax->error("Status cannot be empty.");

                if(empty($checklistItemResult->CompletedBy))
                    $ajax->error("Completed by cannot be empty.");

                if(empty($checklistItemResult->CompletedDate))
                    $ajax->error("Completed date cannot be empty.");

                if(!validDate($checklistItemResult->CompletedDate))
                    $ajax->error("Invalid Date.");

                if($checklistItemResult->save($localDb, $coreDb))
                    $ajax->success("Scout card saved successfully.");

                else
                    $ajax->error("Scout card failed to save.");

                break;

            default:
                $ajax->error("Invalid action.");
                break;

            //endregion
        }

        break;

    case 'delete':

        $recordId = $_POST['recordId'];
        unset($_POST['action']);

        switch($class)
        {
            //region Non Info Classes

            case RobotInfoKeys::class:

                $robotInfoKey = RobotInfoKeys::withId($localDb, $recordId);

                if($robotInfoKey->delete($localDb))
                    $ajax->success("Robot info deleted successfully.");

                else
                    $ajax->error("Robot info failed to delete.");

                break;

            case ScoutCardInfoKeys::class:

                $scoutCardInfoKey = ScoutCardInfoKeys::withId($localDb, $recordId);

                if($scoutCardInfoKey->delete($localDb))
                    $ajax->success("Scout card info deleted successfully.");

                else
                    $ajax->error("Scout card info failed to delete.");

                break;

            case ChecklistItems::class:

                $checklistItem = ChecklistItems::withId($localDb, $recordId);

                if($checklistItem->delete($localDb))
                    $ajax->success("Checklist info deleted successfully.");

                else
                    $ajax->error("Checklist info failed to delete.");

                break;

            case Users::class:

                if(sizeof(Users::getObjects($localDb)) > 1)
                {
                    $user = Users::withId($localDb, $recordId);

                    if($user->Id == getUser()->Id)
                        $ajax->error("You can't delete yourself.");

                    if ($user->delete($localDb))
                        $ajax->success("User deleted successfully.");

                    else
                        $ajax->error("User failed to delete.");
                }

                else
                    $ajax->error("You must have at least 1 user.");

                break;

                //endregion

            //region Info Classes

            case RobotInfo::class:

                $teamId = $_POST['extraArgs']['teamId'];
                $eventId = $_POST['extraArgs']['eventId'];

                if(empty($teamId))
                    $ajax->error("Team id cannot be empty.");

                if(!ctype_digit($teamId))
                    $ajax->error("Team id may only be numeric (0-9).");

                if(empty($eventId))
                    $ajax->error("Event id cannot be empty.");

                if(!ctype_alnum($eventId))
                    $ajax->error("Event id may only be alpha-numeric (A-Z 0-9).");

                $success = true;

                foreach(RobotInfo::getObjects($localDb, null, null, Events::withId($coreDb, $eventId), Teams::withId($coreDb, $teamId)) as $robotInfo)
                    if(!$robotInfo->delete($localDb))
                        $success = false;

                if($success)
                    $ajax->success("Robot info deleted successfully.");

                else
                    $ajax->error("Robot info failed to delete.");

                break;

            case ScoutCardInfo::class:

                $teamId = $_POST['extraArgs']['teamId'];
                $eventId = $_POST['extraArgs']['eventId'];
                $matchId = $_POST['extraArgs']['matchId'];

                if(empty($teamId))
                    $ajax->error("Team id cannot be empty.");

                if(!ctype_digit($teamId))
                    $ajax->error("Team id may only be numeric (0-9).");

                if(empty($eventId))
                    $ajax->error("Event id cannot be empty.");

                if(!ctype_alnum($eventId))
                    $ajax->error("Event id may only be alpha-numeric (A-Z 0-9).");

                if(empty($matchId))
                    $ajax->error("Match id cannot be empty.");

                if(!ctype_alnum(str_replace("_", "", $matchId)))
                    $ajax->error("Match id may only be alpha-numeric (A-Z 0-9).");

                $success = true;

                foreach(ScoutCardInfo::getObjects($localDb, null, null, Events::withId($coreDb, $eventId), Matches::withId($coreDb, $matchId), Teams::withId($coreDb, $teamId)) as $scoutCardInfo)
                    if(!$scoutCardInfo->delete($localDb))
                        $success = false;

                if($success)
                    $ajax->success("Robot info deleted successfully.");

                else
                    $ajax->error("Robot info failed to delete.");

                break;

            case ChecklistItemResults::class:

                $checklistItemResult = ChecklistItemResults::withId($localDb, $recordId);

                if($checklistItemResult->delete($localDb))
                    $ajax->success("Checklist info deleted successfully.");

                else
                    $ajax->error("Checklist info failed to delete.");

                break;

            case RobotMedia::class:

                $robotMedia = RobotMedia::withId($localDb, $recordId);

                if($robotMedia->delete($localDb))
                    $ajax->success("Robot media deleted successfully.");

                else
                    $ajax->error("Robot media failed to delete.");

                break;

            default:
                $ajax->error("Invalid action.");
                break;

                //endregion
        }

        break;

    default:
        $ajax->error("Invalid action.");
        break;
}

/**
 * Checks if text is valid alpha numeric value, including spaces
 * @param string $text to check if valid alpha numeric
 * @return bool
 */
function validAlnum($text)
{
    $text = str_replace(' ','', $text);
    $text = str_replace('.','', $text);

    return ctype_alnum(trim($text)) || empty($text);
}

/**
 * Checks if text is valid date string. Ex: 1997-08-22 19:45:36
 * @param string $text to check if valid date
 * @return bool
 */
function validDate($text)
{
    $text = str_replace(' ','', $text);
    $text = str_replace('-','', $text);
    $text = str_replace(':','', $text);

    return ctype_alnum(trim($text));
}