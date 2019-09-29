<?php
require_once("../config.php");
require_once(ROOT_DIR . "/classes/Ajax.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfo.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItems.php");

$ajax = new Ajax();

if(!getUser()->IsAdmin)
    $ajax->error('You must be logged in as an administrator to access this page.');

switch ($_POST['action'])
{
    case 'save':

        $class = $_POST['class'];

        unset($_POST['action']);
        unset($_POST['class']);

        switch($class)
        {
            case RobotInfoKeys::class:

                $robotInfoKey = RobotInfoKeys::withProperties($_POST['data']);

                if(empty($scoutCardInfoKey->YearId))
                    $ajax->error("Year cannot be empty.");

                if(!ctype_digit($scoutCardInfoKey->YearId))
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

                if(empty($scoutCardInfoKey->YearId))
                    $ajax->error("Year cannot be empty.");

                if(!ctype_digit($scoutCardInfoKey->YearId))
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

                    if(empty($user->Password))
                        $ajax->error("Password may not be empty.");

                    if(!validAlnum($user->Password))
                        $ajax->error("Password may only be alphanumeric (A-Z 0-9).");

                    $user->Password = password_hash($user->Password, PASSWORD_ARGON2ID);
                }

                else
                {
                    $user->UserName == "";
                    $user->Password = "";
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
        }

        break;

    case 'delete':

        $class = $_POST['class'];
        $recordId = $_POST['recordId'];

        unset($_POST['action']);
        unset($_POST['class']);

        switch($class)
        {
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
        }

        break;
}

function validAlnum($text)
{
    return ctype_alnum(trim(str_replace(' ','', $text)));
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