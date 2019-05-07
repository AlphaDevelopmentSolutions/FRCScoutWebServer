<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//define all constants
define('VERSION', 'v2019.2.4.0');

//define error codes
define('FILE_WRITE_FAIL_CODE' , '5x01');
define('CURL_FAIL_CODE' , '5x02');

//verify the server is setup properly
if(file_exists(__DIR__ . '/classes/Keys.php'))
    require_once('classes/Keys.php');
else if (strpos($_SERVER['REQUEST_URI'], 'install.php') < 1)
    header('Location: ' . '/install.php');

//define root paths
define('ROBOT_MEDIA_DIR', __DIR__ . '/html/assets/robot-media/');
define('ROBOT_MEDIA_URL', URL_PATH . '/assets/robot-media/');
define('ROOT_DIR', __DIR__);

//require all necessary files
require_once(ROOT_DIR . '/classes/Database.php');
require_once(ROOT_DIR . '/classes/Table.php');
require_once(ROOT_DIR . '/classes/Users.php');
require_once(ROOT_DIR . '/interfaces/AllianceColors.php');

//if the session doe not exist, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//set the user var
$user = !empty($_SESSION['user']) ? unserialize($_SESSION['user']) : null;

require_once(ROOT_DIR . "/classes/Header.php");
require_once(ROOT_DIR . "/classes/NavBar.php");
require_once(ROOT_DIR . "/classes/NavBarArray.php");
require_once(ROOT_DIR . "/classes/NavBarLink.php");
require_once(ROOT_DIR . "/classes/NavBarLinkArray.php");

/**
 * Returns if the current page was reuqested back from itself
 * @return bool
 */
function isPostBack()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

/**
 * Returns if the current user is logged in
 * @return bool
 */
function loggedIn()
{
    return !is_null(getUser());
}

/**
 * Returns the currently logged in user, if any
 * @return Users|null
 */
function getUser()
{
    return !empty($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
}
?>
