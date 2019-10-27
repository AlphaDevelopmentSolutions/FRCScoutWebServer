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
define('VERSION', 'v4.1.0');
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

define('ROOT_URL', 'https://' . $_SERVER['SERVER_NAME']);
define('URL_PATH', '/');

/**
 * MEDIA FILES
 */
define('ROBOT_MEDIA_DIR', ROOT_DIR . '/assets/robot-media/originals/' . getCoreAccount()->RobotMediaDir . '/');
define('ROBOT_MEDIA_THUMBS_DIR', ROOT_DIR . '/assets/robot-media/thumbs/' . getCoreAccount()->RobotMediaDir . '/');
define('INCLUDES_DIR', ROOT_DIR . '/includes/');

define('ROBOT_MEDIA_URL', '/assets/robot-media/originals/' . getCoreAccount()->RobotMediaDir . '/');
define('ROBOT_MEDIA_THUMBS_URL', '/assets/robot-media/thumbs/' . getCoreAccount()->RobotMediaDir . '/');

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

define('APP_NAME', 'APP_NAME');
define('PRIMARY_COLOR', 'PRIMARY_COLOR');
define('PRIMARY_COLOR_DARK', 'PRIMARY_COLOR_DARK');

define('BLUE_ALLIANCE_KEY', 'BLUE_ALLIANCE_KEY');

require_once(ROOT_DIR . "/classes/Header.php");
require_once(ROOT_DIR . "/classes/NavBar.php");
require_once(ROOT_DIR . "/classes/NavBarArray.php");
require_once(ROOT_DIR . "/classes/NavBarLink.php");
require_once(ROOT_DIR . "/classes/NavBarLinkArray.php");


//get core user config and load it into the session
if(isCoreLoggedIn())
{
    if(
        empty($_SESSION[APP_NAME]) ||
        empty($_SESSION[PRIMARY_COLOR]) ||
        empty($_SESSION[PRIMARY_COLOR_DARK])
    )
    {
        foreach (Config::getObjects($localDb) as $config)
        {
            $_SESSION[$config->Key] = $config->Value;
        }
    }
}

//get the core config and load it into the session
if(empty($_SESSION[BLUE_ALLIANCE_KEY]))
{
    foreach(CoreConfig::getObjects($coreDb) as $config)
    {
        $_SESSION[$config->Key] = $config->Value;
    }
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
