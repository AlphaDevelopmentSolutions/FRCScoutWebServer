<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once('classes/Keys.php');
require_once('classes/Database.php');
require_once('classes/Users.php');

define('VERSION', '1.0.0');
define('ROBOT_MEDIA_DIR', __DIR__ . '/html/assets/robot-media/');
define('ROBOT_MEDIA_URL', URL_PATH . '/assets/robot-media/');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = !empty($_SESSION['user']) ? unserialize($_SESSION['user']) : null;

function isPostBack()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function loggedIn()
{
    return !empty($_SESSION['user']) && !empty(unserialize($_SESSION['user']));
}

define('FILE_WRITE_FAIL_CODE' , '5x01');
define('CURL_FAIL_CODE' , '5x02');

?>
