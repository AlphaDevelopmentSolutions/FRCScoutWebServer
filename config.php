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
define('VERSION', '4.0.0');
define('ROOT_DIR', __DIR__);

/**
 * REQUIRED FILES
 */
require_once(ROOT_DIR . '/classes/Keys.php');
require_once(ROOT_DIR . '/classes/Database.php');
require_once(ROOT_DIR . '/classes/CoreDatabase.php');
require_once(ROOT_DIR . '/classes/LocalDatabase.php');
require_once(ROOT_DIR . '/classes/tables/Table.php');
require_once(ROOT_DIR . '/classes/tables/local/Users.php');
require_once(ROOT_DIR . '/classes/tables/local/Config.php');
require_once(ROOT_DIR . '/classes/tables/core/CoreConfig.php');
require_once(ROOT_DIR . '/classes/tables/core/Accounts.php');
require_once(ROOT_DIR . '/interfaces/AllianceColors.php');

/**
 * LOAD CONFIGS
 */
if(isCoreLoggedIn())
    define('DB_NAME', getCoreAccount()->DbId);
else if($bypassCoreCheck != true && !isCoreLoggedIn())
    redirect('/');

$coreDb = new CoreDatabase();

if(isCoreLoggedIn())
    $localDb = new LocalDatabase();

if(isCoreLoggedIn())
{
    foreach (Config::getObjects($localDb) as $config)
    {
        define($config->Key, $config->Value);
    }
}

foreach(CoreConfig::getObjects($coreDb) as $config)
{
    define($config->Key, $config->Value);
}

define('ROOT_URL', 'https://' . $_SERVER['SERVER_NAME']);
define('URL_PATH', '/');

/**
 * MEDIA FILES
 */
if(isCoreLoggedIn())
{
    define('ROBOT_MEDIA_DIR', ROOT_DIR . '/assets/robot-media/originals/' . getCoreAccount()->RobotMediaDir . '/');
    define('ROBOT_MEDIA_THUMBS_DIR', ROOT_DIR . '/assets/robot-media/thumbs/' . getCoreAccount()->RobotMediaDir . '/');

    define('ROBOT_MEDIA_URL', '/assets/robot-media/originals/' . getCoreAccount()->RobotMediaDir . '/');
    define('ROBOT_MEDIA_THUMBS_URL', '/assets/robot-media/thumbs/' . getCoreAccount()->RobotMediaDir . '/');
}

define('INCLUDES_DIR', ROOT_DIR . '/includes/');

define('YEAR_MEDIA_URL', '/assets/year-media/');
define('IMAGES_URL', '/assets/images/');

define('CSS_URL', '/css/');
define('JS_URL', '/js/');
define('AJAX_URL', '/ajax/');


define('PAGES_URL', URL_PATH . 'pages/');
define('ADMIN_URL', PAGES_URL . 'admin/');
define('CHECKLISTS_URL', PAGES_URL . 'checklists/');
define('EVENTS_URL', PAGES_URL . 'events/');
define('MATCHES_URL', PAGES_URL . 'matches/');
define('STATS_URL', PAGES_URL . 'stats/');
define('TEAMS_URL', PAGES_URL . 'teams/');
define('YEARS_URL', PAGES_URL . 'years/');

require_once(ROOT_DIR . "/classes/Header.php");
require_once(ROOT_DIR . "/classes/NavBar.php");
require_once(ROOT_DIR . "/classes/NavBarArray.php");
require_once(ROOT_DIR . "/classes/NavBarLink.php");
require_once(ROOT_DIR . "/classes/NavBarLinkArray.php");

//These are held as placeholders for programming usage
//Primarily so the IDE will auto-complete
if(false)
{
    define('APP_NAME', '');
    define('PRIMARY_COLOR', '');
    define('PRIMARY_COLOR_DARK', '');

    define('BLUE_ALLIANCE_KEY', '');
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
function isLoggedIn()
{
    return !is_null(getUser());
}

/**
 * Returns if the current user is logged into the core of the app
 * @return bool
 */
function isCoreLoggedIn()
{
    return !is_null(getCoreAccount());
}

/**
 * Returns the currently logged in user, if any
 * @return Accounts|null
 */
function getCoreAccount()
{
    return !empty($_SESSION['coreAccount']) ? unserialize($_SESSION['coreAccount']) : null;
}

/**
 * Sets the core account
 * @param $account Accounts account to add
 */
function setCoreAccount($account)
{
    if(!empty($account))
    {
        define('DB_NAME', $account->DbId);

        define('ROBOT_MEDIA_DIR', ROOT_DIR . '/assets/robot-media/originals/' . $account->RobotMediaDir . '/');
        define('ROBOT_MEDIA_THUMBS_DIR', ROOT_DIR . '/assets/robot-media/thumbs/' . $account->RobotMediaDir . '/');

        define('ROBOT_MEDIA_URL', '/assets/robot-media/originals/' . $account->RobotMediaDir . '/');
        define('ROBOT_MEDIA_THUMBS_URL', '/assets/robot-media/thumbs/' . $account->RobotMediaDir . '/');

        $_SESSION['coreAccount'] = serialize($account);
    }
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
 * Redirects to specified url
 * @param $url string path to redirect to
 */
function redirect($url)
{
    header('Location: ' . $url);
    die();
}
?>
