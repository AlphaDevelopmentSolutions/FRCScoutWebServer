<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//start session
if (session_status() == PHP_SESSION_NONE)
    session_start();

/**
 * CONSTANTS
 */
define('VERSION', 'v2019.3.0.0');
define('ROOT_DIR', __DIR__);

/**
 * ERROR CORDS
 */
define('FILE_WRITE_FAIL_CODE' , '5x01');
define('CURL_FAIL_CODE' , '5x02');


verifyInstall();

/**
 * REQUIRED FILES
 */
require_once(ROOT_DIR . '/classes/Database.php');
require_once(ROOT_DIR . '/classes/tables/Table.php');
require_once(ROOT_DIR . '/classes/tables/Users.php');
require_once(ROOT_DIR . '/classes/tables/Config.php');
require_once(ROOT_DIR . '/interfaces/AllianceColors.php');

/**
 * LOAD CONFIGS
 */
$configs = Config::getObjects();
foreach($configs as $config)
{
    define($config->Key, $config->Value);
}

/**
 * MEDIA FILES
 */
define('ROBOT_MEDIA_DIR', __DIR__ . '/html/assets/robot-media/');
define('ROBOT_MEDIA_URL', URL_PATH . '/assets/robot-media/');
define('YEAR_MEDIA_URL', URL_PATH . '/assets/year-media/');
require_once(ROOT_DIR . "/classes/Header.php");
require_once(ROOT_DIR . "/classes/NavBar.php");
require_once(ROOT_DIR . "/classes/NavBarArray.php");
require_once(ROOT_DIR . "/classes/NavBarLink.php");
require_once(ROOT_DIR . "/classes/NavBarLinkArray.php");


//set the user var
$user = getUser();

//These are held as placeholders for programming usage
//Primarily so the IDE will auto-complete
if(false)
{
    define('APP_NAME', '');
    define('TEAM_NUMBER', '');
    define('TEAM_NAME', '');
    define('URL_PATH', '');
    define('BLUE_ALLIANCE_KEY', '');
    define('API_KEY', '');
}

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

/**
 * Verifies the install on then server
 * If not valid install, redirects to the install page
 */
function verifyInstall()
{
    if(file_exists(__DIR__ . '/classes/Keys.php'))
        require_once('classes/Keys.php');
    else if (strpos($_SERVER['REQUEST_URI'], 'install.php') < 1)
        header('Location: ' . '/install.php');
}
?>
