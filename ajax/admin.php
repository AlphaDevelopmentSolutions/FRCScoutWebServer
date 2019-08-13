<?php
require_once("../config.php");
require_once(ROOT_DIR . "/classes/Ajax.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
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

                $robotInfoKey = RobotInfoKeys::withProperties($_POST);

                if(empty($robotInfoKey->KeyState))
                    $ajax->error("Key state cannot be empty.");

                if(!validAlnum($robotInfoKey->KeyState))
                    $ajax->error("Key state may only be alpha-numeric (A-Z 0-9).");

                if(empty($robotInfoKey->KeyName))
                    $ajax->error("Key name cannot be empty.");

                if(!validAlnum($robotInfoKey->KeyName))
                    $ajax->error("Key name may only be alphanumeric (A-Z 0-9).");

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

                $scoutCardInfoKey = ScoutCardInfoKeys::withProperties($_POST);

                if(empty($scoutCardInfoKey->KeyState))
                    $ajax->error("Key state cannot be empty.");

                if(!validAlnum($scoutCardInfoKey->KeyState))
                    $ajax->error("Key state may only be alpha-numeric (A-Z 0-9).");

                if(empty($scoutCardInfoKey->KeyName))
                    $ajax->error("Key name cannot be empty.");

                if(!validAlnum($scoutCardInfoKey->KeyName))
                    $ajax->error("Key name may only be alphanumeric (A-Z 0-9).");

                if(empty($scoutCardInfoKey->SortOrder))
                    $ajax->error("Sort order cannot be empty.");

                if(!ctype_digit($scoutCardInfoKey->SortOrder))
                    $ajax->error("Sort order may only be numeric (0-9).");

                if(!ctype_digit($scoutCardInfoKey->MinValue) && $scoutCardInfoKey->MinValue != "")
                    $ajax->error("Min value may only be numeric (0-9).");

                if(!ctype_digit($scoutCardInfoKey->MaxValue)  && $scoutCardInfoKey->MaxValue != "")
                    $ajax->error("Max value may only be numeric (0-9).");

                if(!ctype_digit($scoutCardInfoKey->NullZeros))
                    $ajax->error("Null zeros may only be 1 (Yes) or 0 (No).");

                if($scoutCardInfoKey->NullZeros != 0 && $scoutCardInfoKey->NullZeros != 1)
                    $ajax->error("Null zeros may only be 1 (Yes) or 0 (No).");

                if(!ctype_digit($scoutCardInfoKey->IncludeInStats))
                    $ajax->error("Include in stats may only be 1 (Yes) or 0 (No).");

                if($scoutCardInfoKey->IncludeInStats != 0 && $scoutCardInfoKey->IncludeInStats != 1)
                    $ajax->error("Include in stats may only be 1 (Yes) or 0 (No).");

                if(!ctype_alpha($scoutCardInfoKey->DataType))
                    $ajax->error("Datatype may only be alphanumeric (A-Z 0-9).");

                if($scoutCardInfoKey->save())
                    $ajax->success("Scout card info saved successfully.");

                else
                    $ajax->error("Scout card info failed to save.");

                break;

            case ChecklistItems::class:

                $checklistItem = ChecklistItems::withProperties($_POST);

                if(empty($checklistItem->Title))
                    $ajax->error("Title cannot be empty.");

                if(!validAlnum($checklistItem->Title))
                    $ajax->error("Title may only be alpha-numeric (A-Z 0-9).");

                if(empty($checklistItem->Description))
                    $ajax->error("Description cannot be empty.");

                if(!validAlnum($checklistItem->Description))
                    $ajax->error("Description may only be alphanumeric (A-Z 0-9).");

                if($checklistItem->save())
                    $ajax->success("Checklist info saved successfully.");

                else
                    $ajax->error("Checklist info failed to save.");

                break;

            case Users::class:

                $user = Users::withProperties($_POST);

                if(empty($user->FirstName))
                    $ajax->error("First name cannot be empty.");

                if(!validAlnum($user->FirstName))
                    $ajax->error("First name may only be alpha-numeric (A-Z 0-9).");

                if(empty($user->LastName))
                    $ajax->error("Last name cannot be empty.");

                if(!validAlnum($user->LastName))
                    $ajax->error("Last name may only be alphanumeric (A-Z 0-9).");

                if(empty($user->UserName))
                    $ajax->error("Username cannot be empty.");

                if(!validAlnum($user->UserName))
                    $ajax->error("Username may only be alphanumeric (A-Z 0-9).");

                if(!validAlnum($user->Password))
                    $ajax->error("Password may only be alphanumeric (A-Z 0-9).");

                if(empty($user->Id))
                    $user->Password = md5($user->Password);

                if($user->IsAdmin != 0 && $user->IsAdmin != 1)
                    $ajax->error("Admin flag may only be 1 (Yes) or 0 (No).");

                if(!ctype_digit($user->IsAdmin))
                    $ajax->error("Admin flag may only be numeric (0-9).");


                if($user->save())
                    $ajax->success("User saved successfully.");

                else
                    $ajax->error("User failed to save.");

                break;

            case Config::class:

                $config = Config::withProperties($_POST);
                $prevConfig = Config::withId($config->Id);

                if($config->Key != $prevConfig->Key)
                    $ajax->error("Key cannot be changed");

                if(empty($config->Value))
                    $ajax->error("Value name cannot be empty.");

                if(!validAlnum($config->Value))
                    $ajax->error("Value may only be alpha-numeric (A-Z 0-9).");

                if($config->save())
                    $ajax->success("Config saved successfully.");

                else
                    $ajax->error("User failed to save.");

                break;
        }

        break;

    case 'delete':

        $class = $_POST['class'];

        unset($_POST['action']);
        unset($_POST['class']);

        switch($class)
        {
            case RobotInfoKeys::class:

                $robotInfoKey = RobotInfoKeys::withProperties($_POST);

                if($robotInfoKey->delete())
                    $ajax->success("Robot info deleted successfully.");

                else
                    $ajax->error("Robot info failed to delete.");

                break;

            case ScoutCardInfoKeys::class:

                $scoutCardInfoKey = ScoutCardInfoKeys::withProperties($_POST);

                if($scoutCardInfoKey->delete())
                    $ajax->success("Scout card info deleted successfully.");

                else
                    $ajax->error("Scout card info failed to delete.");

                break;

            case ChecklistItems::class:

                $checklistItem = ChecklistItems::withProperties($_POST);

                if($checklistItem->delete())
                    $ajax->success("Checklist info deleted successfully.");

                else
                    $ajax->error("Checklist info failed to delete.");

                break;

            case Users::class:

                $user = Users::withProperties($_POST);

                if($user->delete())
                    $ajax->success("User deleted successfully.");

                else
                    $ajax->error("User failed to delete.");

                break;
        }

        break;
}

function validAlnum($text)
{
    return ctype_alnum(trim(str_replace(' ','', $text)));
}