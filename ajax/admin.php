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
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfo.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItemResults.php");

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

                if(!validAlnum($robotInfoKey->KeyState))
                    $ajax->error("Game state may only be alpha-numeric (A-Z 0-9).");

                if(empty($robotInfoKey->KeyName))
                    $ajax->error("Info name cannot be empty.");

                if(!validAlnum($robotInfoKey->KeyName))
                    $ajax->error("Info name may only be alphanumeric (A-Z 0-9).");

                if(empty($robotInfoKey->SortOrder))
                    $ajax->error("Sort order cannot be empty.");

                if(!ctype_digit($robotInfoKey->SortOrder))
                    $ajax->error("Sort order may only be numeric (0-9).");

                if($robotInfoKey->save())
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

                if(!validAlnum($scoutCardInfoKey->KeyState))
                    $ajax->error("Game state may only be alpha-numeric (A-Z 0-9).");

                if(empty($scoutCardInfoKey->KeyName))
                    $ajax->error("Info name cannot be empty.");

                if(!validAlnum($scoutCardInfoKey->KeyName))
                    $ajax->error("Info name may only be alphanumeric (A-Z 0-9).");

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

                if($scoutCardInfoKey->DataType != ScoutCardInfoKeys::withId($scoutCardInfoKey->Id)->DataType && count(ScoutCardInfo::getObjects($scoutCardInfoKey)) > 0)
                    $ajax->error("You can't change datatypes if you already have scout cards populated under this key.");

                if($scoutCardInfoKey->save())
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

                if(!validAlnum($checklistItem->Title))
                    $ajax->error("Title may only be alpha-numeric (A-Z 0-9).");

                if(empty($checklistItem->Description))
                    $ajax->error("Description cannot be empty.");

                if(!validDescription($checklistItem->Description))
                    $ajax->error("Description contains invalid characters.");

                if($checklistItem->save())
                    $ajax->success("Checklist info saved successfully.");

                else
                    $ajax->error("Checklist info failed to save.");

                break;

            case Users::class:

                $user = Users::withProperties($_POST['data']);

                if(empty($user->FirstName))
                    $ajax->error("First name cannot be empty.");

                if(!validAlnum($user->FirstName))
                    $ajax->error("First name may only be alpha-numeric (A-Z 0-9).");

                if(empty($user->LastName))
                    $ajax->error("Last name cannot be empty.");

                if(!validAlnum($user->LastName))
                    $ajax->error("Last name may only be alphanumeric (A-Z 0-9).");

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
                        $user->Password = Users::withId($user->Id)->Password;
                }
                else
                {
                    $user->UserName = null;
                    $user->Password = null;
                }

                if($user->save())
                    $ajax->success("User saved successfully.");

                else
                    $ajax->error("User failed to save.");

                break;

            case Config::class:

                $configs = Config::getObjects();

                $data = $_POST['data'];

                foreach ($configs as $config)
                {
                    switch ($config->Key)
                    {
                        case "APP_NAME":
                            $config->Value = $data['AppName'];
                            break;

                        case "API_KEY":
                            $config->Value = $data['ApiKey'];
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

                    if (!validAlnum($config->Value))
                        $ajax->error("Value may only be alpha-numeric (A-Z 0-9).");

                    if (!$config->save())
                        $ajax->error("User failed to save.");
                }

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

                if(!validAlnum($robotInfo->PropertyValue))
                    $ajax->error("Value may only be alphanumeric (A-Z 0-9).");

                if(empty($robotInfo->PropertyKeyId))
                    $ajax->error("Property key id cannot be empty.");

                if(!ctype_digit($robotInfo->PropertyKeyId))
                    $ajax->error("Property key id may only be numeric (0-9).");

                if($robotInfo->save())
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

                if(!validAlnum($scoutCardInfo->CompletedBy))
                    $ajax->error("Completed by name may only be alpha-numeric (A-Z 0-9).");

                if(!validAlnum($scoutCardInfo->PropertyValue))
                    $ajax->error("Value may only be alphanumeric (A-Z 0-9).");

                if(empty($scoutCardInfo->PropertyKeyId))
                    $ajax->error("Property key id cannot be empty.");

                if(!ctype_digit($scoutCardInfo->PropertyKeyId))
                    $ajax->error("Property key id may only be numeric (0-9).");

                if($scoutCardInfo->save())
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

                if(!validAlnum($checklistItemResult->Status))
                    $ajax->error("Status may only be alpha-numeric (A-Z 0-9).");

                if(empty($checklistItemResult->CompletedBy))
                    $ajax->error("Completed by cannot be empty.");

                if(!validAlnum($checklistItemResult->CompletedBy))
                    $ajax->error("Completed by may only be alpha-numeric (A-Z 0-9).");

                if(empty($checklistItemResult->CompletedDate))
                    $ajax->error("Completed date cannot be empty.");

                if(!validDate($checklistItemResult->CompletedDate))
                    $ajax->error("Invalid Date.");

                if($checklistItemResult->save())
                    $ajax->success("Scout card saved successfully.");

                else
                    $ajax->error("Scout card failed to save.");

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

                $robotInfoKey = RobotInfoKeys::withId($recordId);

                if($robotInfoKey->delete())
                    $ajax->success("Robot info deleted successfully.");

                else
                    $ajax->error("Robot info failed to delete.");

                break;

            case ScoutCardInfoKeys::class:

                $scoutCardInfoKey = ScoutCardInfoKeys::withId($recordId);

                if($scoutCardInfoKey->delete())
                    $ajax->success("Scout card info deleted successfully.");

                else
                    $ajax->error("Scout card info failed to delete.");

                break;

            case ChecklistItems::class:

                $checklistItem = ChecklistItems::withId($recordId);

                if($checklistItem->delete())
                    $ajax->success("Checklist info deleted successfully.");

                else
                    $ajax->error("Checklist info failed to delete.");

                break;

            case Users::class:

                if(sizeof(Users::getObjects()) > 1)
                {
                    $user = Users::withId($recordId);

                    if($user->Id == getUser()->Id)
                        $ajax->error("You can't delete yourself.");

                    if ($user->delete())
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

                foreach(RobotInfo::getObjects(null, null, Events::withId($eventId), Teams::withId($teamId)) as $robotInfo)
                    if(!$robotInfo->delete())
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

                foreach(ScoutCardInfo::getObjects(null, null, Events::withId($eventId), Matches::withId($matchId), Teams::withId($teamId)) as $robotInfo)
                    if(!$robotInfo->delete())
                        $success = false;

                if($success)
                    $ajax->success("Robot info deleted successfully.");

                else
                    $ajax->error("Robot info failed to delete.");

                break;

                //endregion
        }

        break;
}

function validAlnum($text)
{
    return ctype_alnum(trim(str_replace(' ','', $text)));
}

function validDate($text)
{
    $text = str_replace(' ','', $text);
    $text = str_replace('-','', $text);
    $text = str_replace(':','', $text);

    return ctype_alnum(trim($text));
}

function validDescription($text)
{
    $text = str_replace(' ','', $text);
    $text = str_replace('.','', $text);
    $text = str_replace(',','', $text);
    $text = str_replace('?','', $text);
    $text = str_replace('!','', $text);
    return ctype_alnum(trim($text));
}