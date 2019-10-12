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
        $username = $_POST['username'];
        $password = $_POST['password'];
        $redirectUrl = $_POST['url'];

        if(!empty($username) && !empty($password))
        {
            $user = new Users();
            $user = $user->login($username, $password);
            if(!empty($user))
            {
                $_SESSION['user'] = serialize($user);
                $ajax->success('Successfully logged in.');
            }
        }

        $ajax->error('Invalid username or password');
        break;

    case 'logout_user':
        unset($_SESSION['user']);
        $ajax->success('Successfully logged out.');
        break;

    case 'login_core':
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(!empty($username) && !empty($password))
        {
            $user = new Accounts();
            $user = $user->login($username, $password);
            if(!empty($user))
            {
                $_SESSION['coreAccount'] = serialize($user);
                $ajax->success('Successfully logged in.');
            }
        }

        $ajax->error('Invalid username or password');
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


        if (empty($username) || !validAlnum($username) || strlen($username) < 6)
            $ajax->error('Username may only include A-Z 0-9 and must be at least 6 characters.');

        if (empty($email))
            $ajax->error('Email must not be empty.');

        //check if any of the accounts details are already taken
        $accounts = Accounts::getObjects();
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
            $ajax->error('Username may only include A-F 0-9 and must be 6 characters.');

        if (empty($secondaryColor) || !validAlnum($secondaryColor) || strlen($secondaryColor) != 6)
            $ajax->error('Username may only include A-F 0-9 and must be 6 characters.');

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

            $db = new Database($dbId);

            //create all required tables
            if (importSqlFile($db, '../databases/v' . VERSION . '.sql')) {
                unset($db);

                define('DB_NAME', $dbId);

                //add all the configs to the DB
                $conf = new Config();
                $conf->Key = 'APP_NAME';
                $conf->Value = $appName;
                $conf->save();

                $conf = new Config();
                $conf->Key = 'API_KEY';
                $conf->Value = $apiKey;
                $conf->save();

                $conf = new Config();
                $conf->Key = 'PRIMARY_COLOR';
                $conf->Value = $primaryColor;
                $conf->save();

                $conf = new Config();
                $conf->Key = 'PRIMARY_COLOR_DARK';
                $conf->Value = $secondaryColor;
                $conf->save();

                $user = new Users();
                $user->FirstName = $adminFirstName;
                $user->LastName = $adminLastName;
                $user->UserName = $adminUsername;
                $user->Password = password_hash($adminPassword, PASSWORD_ARGON2ID);
                $user->IsAdmin = 1;
                $user->save();

                $account = new Accounts();
                $account->TeamId = $teamNumber;
                $account->Email = $email;
                $account->Username = $username;
                $account->Password = password_hash($password, PASSWORD_ARGON2ID);
                $account->DbId = $dbId;
                $account->save();
            } else
                $ajax->error('Error importing SQL tables.');

            $ajax->success('Account created successfully!');
        }
        else
            $ajax->error('Captcha invalid. Please try again.');

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