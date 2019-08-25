<?php
require_once("../config.php");
require_once(ROOT_DIR . "/classes/Ajax.php");

$ajax = new Ajax();

switch ($_POST['action'])
{
    case 'create':

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $retypePassword = $_POST['retypePassword'];

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
        foreach($accounts as $account)
        {
            if($account->Username == $username)
                $ajax->error('The username provided is already in use.');

            if($account->Email == $email)
                $ajax->error('The email address provided is already in use.');
        }

        if (empty($password) || !validAlnum($password) || strlen($password) < 6)
            $ajax->error('Password may only include A-Z 0-9 and must be at least 6 characters.');

        if ($password != $retypePassword)
            $ajax->error('Passwords do not match.');

        if (empty($teamNumber) || !ctype_digit($teamNumber))
            $ajax->error('Team number may only be 0-9.');

        if (empty($appName) || !validAlnum($appName) || strlen($appName) < 6)
            $ajax->error('App Name may only include A-Z 0-9 and must be at least 6 characters.');

        if (empty($primaryColor) || !validAlnum($primaryColor) || strlen($primaryColor) != 6)
            $ajax->error('Username may only include A-F 0-9 and must be 6 characters.');

        if (empty($secondaryColor) || !validAlnum($secondaryColor) || strlen($secondaryColor) != 6)
            $ajax->error('Username may only include A-F 0-9 and must be 6 characters.');

        $db = new Database();
        $uidSuccess = false;
        $dbId = "";

        do
        {
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

        $db->close();

        $db = new Database($dbId);

        //create all required tables
        if(importSqlFile($db, '../databases/v' . VERSION . '.sql'))
        {
            $db->close();

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


            $account = new Accounts();
            $account->TeamId = $teamNumber;
            $account->Email = $email;
            $account->Username = $username;
            $account->Password = sha1($password);
            $account->DbId = $dbId;
            $account->save();
        }
        else
            $ajax->error('Error importing SQL tables.');

        $ajax->success('Account created successfully!');

        break;
}

/**
 * Import SQL File
 * @param $pdo
 * @param $sqlFile
 * @param null $tablePrefix
 * @param null $InFilePath
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

function validAlnum($text)
{
    return ctype_alnum(trim(str_replace(' ', '', $text)));
}