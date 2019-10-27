<?php
$bypassCoreCheck = true;
require_once("../config.php");
require_once(ROOT_DIR . "/classes/Ajax.php");
require_once(ROOT_DIR . "/classes/tables/local/Users.php");
require_once(ROOT_DIR . "/classes/tables/core/Accounts.php");

$ajax = new Ajax();

switch ($_POST['action'])
{
    case 'login_user':

        if(isCoreLoggedIn())
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $redirectUrl = $_POST['url'];

            if (!empty($username) && !empty($password))
            {
                $user = new Users();
                $user = $user->login($localDb, $username, $password);
                if (!empty($user))
                {
                    $_SESSION['user'] = serialize($user);
                    $ajax->success('Successfully logged in.');
                }
            }

            $ajax->error('Invalid username or password');
        }

        $ajax->error('You must be logged in to access this page.');

        break;

    case 'logout_user':
        unset($_SESSION['user']);
        $ajax->success('Successfully logged out.');
        break;

    case 'login_core':
        $username = $_POST['username'];
        $password = $_POST['password'];
        $captchaKey = $_POST['captchaKey'];

        if(empty($captchaKey))
            $ajax->error('Captcha must be completed.');

        if(!empty($username) && !empty($password))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                "secret=" . CAPTCHA_SERVER_SECRET . "&response=$captchaKey&remoteip=" . $_SERVER['HTTP_CLIENT_IP']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);

            $response = json_decode($response, true);

            if($response['success'])
            {
                $user = new Accounts();
                $user->Username = $username;
                $user->Password = $password;

                if ($user->login($coreDb))
                {
                    $_SESSION['coreAccount'] = serialize($user);
                    $ajax->success('Successfully logged in.');
                }

                $ajax->error('Invalid username or password.');
            }

            $ajax->error('Captcha invalid. Please try again.');
        }

        $ajax->error('Username and password cannot be empty.');
        break;

    case 'logout_core':
        unset($_SESSION);
        session_destroy();
        $ajax->success('Successfully logged out.');
        break;

    case 'create_core':
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $retypePassword = $_POST['retypePassword'];

        $adminFirstName = $_POST['adminFirstName'];
        $adminLastName = $_POST['adminLastName'];
        $adminUsername = $_POST['adminUsername'];
        $adminPassword = $_POST['adminPassword'];
        $adminRetypePassword = $_POST['adminRetypePassword'];

        $teamNumber = $_POST['teamNumber'];
        $appName = $_POST['appName'];
        $apiKey = $_POST['apiKey'];
        $primaryColor = $_POST['primaryColor'];
        $secondaryColor = $_POST['secondaryColor'];

        $captchaKey = $_POST['captchaKey'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "secret=" . CAPTCHA_SERVER_SECRET . "&response=$captchaKey&remoteip=" . $_SERVER['HTTP_CLIENT_IP']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);

        $response = json_decode($response, true);

        if($response['success'])
        {
            if (empty($username) || !validAlnum($username) || strlen($username) < 6)
                $ajax->error('Account Username may only include A-Z 0-9 and must be at least 6 characters.');

            if (empty($email))
                $ajax->error('Email must not be empty.');

            //check if any of the accounts details are already taken
            $accounts = Accounts::getObjects($coreDb);
            foreach ($accounts as $account) {
                if ($account->Username == $username)
                    $ajax->error('The username provided is already in use.');

                if ($account->Email == $email)
                    $ajax->error('The email address provided is already in use.');
            }

            if (!validPassword($password))
                $ajax->error('Main account password is invalid. Please review the password requirements.');

            if ($password != $retypePassword)
                $ajax->error('Main account passwords do not match.');

            if (empty($adminFirstName) || !ctype_alpha($adminFirstName))
                $ajax->error('Admin first name may only include A-Z.');

            if (empty($adminLastName) || !ctype_alpha($adminLastName))
                $ajax->error('Admin last name may only include A-Z.');

            if (empty($adminUsername) || !validAlnum($adminUsername) || strlen($adminUsername) < 6)
                $ajax->error('Admin username may only include A-Z 0-9 and must be at least 6 characters.');

            if (!validPassword($adminPassword))
                $ajax->error('Admin password is invalid. Please review the password requirements.');

            if ($adminPassword != $adminRetypePassword)
                $ajax->error('Admin passwords do not match.');

            if (empty($teamNumber) || !ctype_digit($teamNumber))
                $ajax->error('Team number may only be 0-9.');

            if (empty($appName) || !validAlnum($appName) || strlen($appName) < 6)
                $ajax->error('App Name may only include A-Z 0-9 and must be at least 6 characters.');

            if (empty($primaryColor) || !validAlnum($primaryColor) || strlen($primaryColor) != 6)
                $ajax->error('Primary color may only include A-F 0-9 and must be 6 characters.');

            if (empty($secondaryColor) || !validAlnum($secondaryColor) || strlen($secondaryColor) != 6)
                $ajax->error('Primary color may only include A-F 0-9 and must be 6 characters.');

                $db = new Database();
                $uidSuccess = false;
                $dbId = "";

            do {
                //create uuid for the database name
                $dbId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    mt_rand(0, 0xffff),
                    mt_rand(0, 0x0fff) | 0x4000,
                    mt_rand(0, 0x3fff) | 0x8000,
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

                $query = "SELECT ! FROM !.! WHERE ! = ?";
                $cols[] = 'SCHEMA_NAME';
                $cols[] = 'INFORMATION_SCHEMA';
                $cols[] = 'SCHEMATA';
                $cols[] = 'SCHEMA_NAME';

                $args[] = $dbId;

                //query to see if that db name is taken
                $results = $db->query($query, $cols, $args);

                //if taken, re-run script
                if (sizeof($results) == 0)
                    $uidSuccess = true;

            } while (!$uidSuccess);

            $query = "CREATE SCHEMA !";
            $cols = array();
            $cols[] = $dbId;

            //create the DB
            $db->query($query, $cols);

            unset($db);

            $localDb = new Database($dbId);

            //create all required tables
            if (importSqlFile($localDb, '../databases/v' . VERSION . '.sql'))
            {
                define('DB_NAME', $dbId);

                //add all the configs to the DB
                $conf = new Config();
                $conf->Key = APP_NAME;
                $conf->Value = $appName;
                $conf->save($localDb);

                $conf = new Config();
                $conf->Key = PRIMARY_COLOR;
                $conf->Value = $primaryColor;
                $conf->save($localDb);

                $conf = new Config();
                $conf->Key = PRIMARY_COLOR_DARK;
                $conf->Value = $secondaryColor;
                $conf->save($localDb);

                $user = new Users();
                $user->FirstName = $adminFirstName;
                $user->LastName = $adminLastName;
                $user->UserName = $adminUsername;
                $user->Password = password_hash($adminPassword, PASSWORD_ARGON2ID);
                $user->IsAdmin = 1;
                $user->save($localDb);

                $account = new Accounts();
                $account->TeamId = $teamNumber;
                $account->Email = $email;
                $account->Username = $username;
                $account->Password = password_hash($password, PASSWORD_ARGON2ID);
                $account->DbId = $dbId;
                $account->ApiKey = $apiKey;
                $account->RobotMediaDir = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    mt_rand(0, 0xffff),
                    mt_rand(0, 0x0fff) | 0x4000,
                    mt_rand(0, 0x3fff) | 0x8000,
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
                $account->save($coreDb);
            } else
                $ajax->error('Error importing SQL tables.');

            $ajax->success('Account created successfully!');
        }
        else
            $ajax->error('Captcha invalid. Please try again.');

        break;

    case 'create_core_demo':
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $retypePassword = $_POST['retypePassword'];

        $adminFirstName = $_POST['adminFirstName'];
        $adminLastName = $_POST['adminLastName'];
        $adminUsername = $_POST['adminUsername'];
        $adminPassword = $_POST['adminPassword'];
        $adminRetypePassword = $_POST['adminRetypePassword'];

        $teamNumber = $_POST['teamNumber'];
        $appName = $_POST['appName'];
        $apiKey = $_POST['apiKey'];
        $primaryColor = $_POST['primaryColor'];
        $secondaryColor = $_POST['secondaryColor'];

        $captchaKey = $_POST['captchaKey'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "secret=" . CAPTCHA_SERVER_SECRET . "&response=$captchaKey&remoteip=" . $_SERVER['HTTP_CLIENT_IP']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);

        $response = json_decode($response, true);

        if($response['success'])
        {

            if (empty($username) || !validAlnum($username) || strlen($username) < 6)
                $ajax->error('Account Username may only include A-Z 0-9 and must be at least 6 characters.');

            if (empty($email))
                $ajax->error('Email must not be empty.');

            //check if any of the accounts details are already taken
            $accounts = Accounts::getObjects($coreDb);
            foreach ($accounts as $account) {
                if ($account->Username == $username)
                    $ajax->error('The username provided is already in use.');

                if ($account->Email == $email)
                    $ajax->error('The email address provided is already in use.');
            }

            if (!validPassword($password))
                $ajax->error('Main account password is invalid. Please review the password requirements.');

            if ($password != $retypePassword)
                $ajax->error('Main account passwords do not match.');

            if (empty($adminFirstName) || !ctype_alpha($adminFirstName))
                $ajax->error('Admin first name may only include A-Z.');

            if (empty($adminLastName) || !ctype_alpha($adminLastName))
                $ajax->error('Admin last name may only include A-Z.');

            if (empty($adminUsername) || !validAlnum($adminUsername) || strlen($adminUsername) < 6)
                $ajax->error('Admin username may only include A-Z 0-9 and must be at least 6 characters.');

            if (!validPassword($adminPassword))
                $ajax->error('Admin password is invalid. Please review the password requirements.');

            if ($adminPassword != $adminRetypePassword)
                $ajax->error('Admin passwords do not match.');

            if (empty($teamNumber) || !ctype_digit($teamNumber))
                $ajax->error('Team number may only be 0-9.');

            if (empty($appName) || !validAlnum($appName) || strlen($appName) < 6)
                $ajax->error('App Name may only include A-Z 0-9 and must be at least 6 characters.');

            if (empty($primaryColor) || !validAlnum($primaryColor) || strlen($primaryColor) != 6)
                $ajax->error('Primary color may only include A-F 0-9 and must be 6 characters.');

            if (empty($secondaryColor) || !validAlnum($secondaryColor) || strlen($secondaryColor) != 6)
                $ajax->error('Primary color may only include A-F 0-9 and must be 6 characters.');

            $db = new Database();
            $uidSuccess = false;
            $dbId = "";

            do {
                //create uuid for the database name
                $dbId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    mt_rand(0, 0xffff),
                    mt_rand(0, 0x0fff) | 0x4000,
                    mt_rand(0, 0x3fff) | 0x8000,
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

                $query = "SELECT ! FROM !.! WHERE ! = ?";
                $cols[] = 'SCHEMA_NAME';
                $cols[] = 'INFORMATION_SCHEMA';
                $cols[] = 'SCHEMATA';
                $cols[] = 'SCHEMA_NAME';

                $args[] = $dbId;

                //query to see if that db name is taken
                $results = $db->query($query, $cols, $args);

                //if taken, re-run script
                if (sizeof($results) == 0)
                    $uidSuccess = true;

            } while (!$uidSuccess);

            $query = "CREATE SCHEMA !";
            $cols = array();
            $cols[] = $dbId;

            //create the DB
            $db->query($query, $cols);

            unset($db);
            $localDb = new Database($dbId);

            //create all required tables
            if (importSqlFile($localDb, '../databases/v' . VERSION . '.sql'))
            {

                unset($coreDb);
                $coreDb = new CoreDatabase();

                define('DB_NAME', $dbId);

                //add all the configs to the DB
                $conf = new Config();
                $conf->Key = APP_NAME;
                $conf->Value = $appName;
                $conf->save($localDb);

                $conf = new Config();
                $conf->Key = PRIMARY_COLOR;
                $conf->Value = $primaryColor;
                $conf->save($localDb);

                $conf = new Config();
                $conf->Key = PRIMARY_COLOR_DARK;
                $conf->Value = $secondaryColor;
                $conf->save($localDb);

                $user = new Users();
                $user->FirstName = $adminFirstName;
                $user->LastName = $adminLastName;
                $user->UserName = $adminUsername;
                $user->Password = password_hash($adminPassword, PASSWORD_ARGON2ID);
                $user->IsAdmin = 1;
                $user->save($localDb);

                $account = new Accounts();
                $account->TeamId = $teamNumber;
                $account->Email = $email;
                $account->Username = $username;
                $account->Password = password_hash($password, PASSWORD_ARGON2ID);
                $account->DbId = $dbId;
                $account->ApiKey = $apiKey;
                $account->RobotMediaDir = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    mt_rand(0, 0xffff),
                    mt_rand(0, 0x0fff) | 0x4000,
                    mt_rand(0, 0x3fff) | 0x8000,
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
                $account->save($coreDb);

                require_once(ROOT_DIR . "/classes/tables/core/Years.php");
                require_once(ROOT_DIR . "/classes/tables/core/Events.php");
                require_once(ROOT_DIR . "/classes/tables/core/Demos.php");
                require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
                require_once(ROOT_DIR . "/classes/tables/core/Matches.php");
                require_once(ROOT_DIR . "/classes/tables/local/ChecklistItems.php");
                require_once(ROOT_DIR . "/classes/tables/local/ChecklistItemResults.php");
                require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
                require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfo.php");
                require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
                require_once(ROOT_DIR . "/classes/tables/local/RobotInfo.php");

                $year = Years::withId($coreDb, date('Y'));
                $team = Teams::withId($coreDb, $teamNumber);

                $coreDb->beginTransaction();
                $localDb->beginTransaction();

                $demo = new Demos();
                $demo->AccountId = $account->Id;
                $demo->Expires = date("Y-m-d H:i:s", strtotime('+24 hours'));
                $demo->save($coreDb);

                $obj = new ChecklistItems();
                $obj->YearId = $year->Id;
                $obj->Title = 'Change Battery';
                $obj->Description = 'Grab fully charged batter and check voltage with voltmeter. Only place battery in the robot if the voltage reads 12.4+ volts. Make sure the battery is properly secured.';
                $obj->save($localDb);

                $obj = new ChecklistItems();
                $obj->YearId = $year->Id;
                $obj->Title = 'Check Pneumatic Pressure';
                $obj->Description = 'Ensure STORED TANK pressure exceeds 100 psi. If tank pressure is low, locate drive team member or pit leader to fill tanks.';
                $obj->save($localDb);

                $obj = new ChecklistItems();
                $obj->YearId = $year->Id;
                $obj->Title = 'Deploy Code';
                $obj->Description = 'Ensure code is on robot and if code is deployed, verified by a programmer.';
                $obj->save($localDb);

                $obj = new ChecklistItems();
                $obj->YearId = $year->Id;
                $obj->Title = 'Inspect for Damage or Excessive Wear';
                $obj->Description = 'Ensure all mechanisms are able to function correctly and that they have not sustained major damage. If excessive wear or damage is identified, alert pit leader or drive team.';
                $obj->save($localDb);


                $obj = new ScoutCardInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Pre Game';
                $obj->KeyName = 'Starting Position';
                $obj->SortOrder = 1;
                $obj->MinValue = null;
                $obj->MaxValue = null;
                $obj->NullZeros = false;
                $obj->IncludeInStats = false;
                $obj->DataType = 'TEXT';
                $obj->save($localDb);

                $obj = new ScoutCardInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Pre Game';
                $obj->KeyName = 'Starting Piece';
                $obj->SortOrder = 2;
                $obj->MinValue = null;
                $obj->MaxValue = null;
                $obj->NullZeros = false;
                $obj->IncludeInStats = false;
                $obj->DataType = 'TEXT';
                $obj->save($localDb);

                $obj = new ScoutCardInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Autonomous';
                $obj->KeyName = 'Hatches Secured';
                $obj->SortOrder = 3;
                $obj->MinValue = 0;
                $obj->MaxValue = null;
                $obj->NullZeros = true;
                $obj->IncludeInStats = true;
                $obj->DataType = 'INT';
                $obj->save($localDb);

                $obj = new ScoutCardInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Autonomous';
                $obj->KeyName = 'Cargo Stored';
                $obj->SortOrder = 4;
                $obj->MinValue = 0;
                $obj->MaxValue = null;
                $obj->NullZeros = true;
                $obj->IncludeInStats = true;
                $obj->DataType = 'INT';
                $obj->save($localDb);

                $obj = new ScoutCardInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Teleop';
                $obj->KeyName = 'Hatches Secured';
                $obj->SortOrder = 5;
                $obj->MinValue = 0;
                $obj->MaxValue = null;
                $obj->NullZeros = true;
                $obj->IncludeInStats = true;
                $obj->DataType = 'INT';
                $obj->save($localDb);

                $obj = new ScoutCardInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Teleop';
                $obj->KeyName = 'Cargo Stored';
                $obj->SortOrder = 6;
                $obj->MinValue = 0;
                $obj->MaxValue = null;
                $obj->NullZeros = true;
                $obj->IncludeInStats = true;
                $obj->DataType = 'INT';
                $obj->save($localDb);

                $obj = new ScoutCardInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Post Game';
                $obj->KeyName = 'Notes';
                $obj->SortOrder = 7;
                $obj->MinValue = null;
                $obj->MaxValue = null;
                $obj->NullZeros = false;
                $obj->IncludeInStats = false;
                $obj->DataType = 'TEXT';
                $obj->save($localDb);


                $obj = new RobotInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Pre Game';
                $obj->KeyName = 'Drivetrain';
                $obj->SortOrder = 1;
                $obj->save($localDb);

                $obj = new RobotInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Pre Game';
                $obj->KeyName = 'Robot Weight';
                $obj->SortOrder = 2;
                $obj->save($localDb);

                $obj = new RobotInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Autonomous';
                $obj->KeyName = 'Cargo Stored';
                $obj->SortOrder = 3;
                $obj->save($localDb);

                $obj = new RobotInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Autonomous';
                $obj->KeyName = 'Hatches Secured';
                $obj->SortOrder = 4;
                $obj->save($localDb);

                $obj = new RobotInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Teleop';
                $obj->KeyName = 'Cargo Stored';
                $obj->SortOrder = 5;
                $obj->save($localDb);

                $obj = new RobotInfoKeys();
                $obj->YearId = $year->Id;
                $obj->KeyState = 'Teleop';
                $obj->KeyName = 'Hatches Secured';
                $obj->SortOrder = 6;
                $obj->save($localDb);

                $coreDb->commit();
                $localDb->commit();

                $checklistItems = ChecklistItems::getObjects($localDb);
                $scoutCardInfoKeys = ScoutCardInfoKeys::getObjects($localDb);
                $robotInfoKeys = RobotInfoKeys::getObjects($localDb);

                $coreDb->beginTransaction();
                $localDb->beginTransaction();

                foreach(Events::getObjects($coreDb, $year, $team) as $event)
                {
                    foreach (Matches::getObjects($coreDb, $event) as $match)
                    {
                        foreach ($checklistItems as $checklistItem)
                        {
                            $checklistItemResult = new ChecklistItemResults();
                            $checklistItemResult->ChecklistItemId = $checklistItem->Id;
                            $checklistItemResult->MatchId = $match->Key;
                            $checklistItemResult->Status = (rand(1, 2) == 1) ? ChecklistItemResults::COMPLETE : ChecklistItemResults::INCOMPLETE;
                            $checklistItemResult->CompletedBy = 'Demo User';
                            $checklistItemResult->CompletedDate = date('Y-m-d H:i:s');
                            $checklistItemResult->save($localDb, $coreDb, true);
                        }

                        foreach ($scoutCardInfoKeys as $scoutCardInfoKey)
                        {
                            if ($scoutCardInfoKey->DataType == 'INT')
                            {
                                for($i = 0; $i < 6; $i++)
                                {
                                    switch ($i)
                                    {
                                        case 0:
                                            $teamId = $match->BlueAllianceTeamOneId;
                                            break;

                                        case 1:
                                            $teamId = $match->BlueAllianceTeamTwoId;
                                            break;

                                        case 2:
                                            $teamId = $match->BlueAllianceTeamThreeId;
                                            break;

                                        case 3:
                                            $teamId = $match->RedAllianceTeamOneId;
                                            break;

                                        case 4:
                                            $teamId = $match->RedAllianceTeamTwoId;
                                            break;

                                        case 5:
                                            $teamId = $match->RedAllianceTeamThreeId;
                                            break;

                                        default:
                                            $teamId = $match->BlueAllianceTeamOneId;
                                            break;
                                    }

                                    $scoutCardInfo = new ScoutCardInfo();
                                    $scoutCardInfo->YearId = $year->Id;
                                    $scoutCardInfo->EventId = $match->EventId;
                                    $scoutCardInfo->MatchId = $match->Key;
                                    $scoutCardInfo->TeamId = $teamId;
                                    $scoutCardInfo->CompletedBy = '';
                                    $scoutCardInfo->PropertyValue = rand(0, 10);
                                    $scoutCardInfo->PropertyKeyId = $scoutCardInfoKey->Id;
                                    $scoutCardInfo->save($localDb, $coreDb, true);
                                }
                            }
                        }
                    }
                }

                $coreDb->commit();
                $localDb->commit();

            } else
                $ajax->error('Error importing SQL tables. Please try again.');

            $ajax->success('Account created successfully!');
        }
        else
            $ajax->error('Captcha invalid. Please try again.');

        break;

    default:
        $ajax->error('Invalid Action.');
        break;
}

/**
 * Import SQL File
 * @param $pdo
 * @param $sqlFile
 * @return bool
 */
function importSqlFile($pdo, $sqlFile)
{

    // Enable LOAD LOCAL INFILE
    $pdo->setAttribute(\PDO::MYSQL_ATTR_LOCAL_INFILE, true);

    // Temporary variable, used to store current query
    $tmpLine = '';

    // Read in entire file
    $lines = file($sqlFile);

    // Loop through each line
    foreach ($lines as $line)
    {
        // Skip it if it's a comment
        if (substr($line, 0, 2) == '--' || trim($line) == '')
        {
            continue;
        }

        // Add this line to the current segment
        $tmpLine .= $line;

        // If it has a semicolon at the end, it's the end of the query
        if (substr(trim($line), -1, 1) == ';')
        {
            try
            {
                // Perform the Query
                $pdo->exec($tmpLine);
            } catch (\PDOException $e)
            {
                return false;
            }

            // Reset temp variable to empty
            $tmpLine = '';
        }
    }

    return true;
}

/**
 * Checks if a string / text is a valid alnum including spaces
 * @param $text
 * @return bool
 */
function validAlnum($text)
{
    return ctype_alnum(trim(str_replace(' ', '', $text)));
}

/**
 * Checks if a password is valid based off password requirements
 * @param $password
 * @return bool
 */
function validPassword($password)
{
    return preg_match('~^[a-zA-Z0-9]{8,}~', $password) == 1;
}
?>