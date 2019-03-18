<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'on');
require_once('classes/Keys.php');
require_once('classes/Database.php');
require_once('classes/Users.php');

define('ROBOT_MEDIA_DIR', __DIR__ . '/html/assets/robot-media/');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = unserialize($_SESSION['user']);

function isPostBack()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function loggedIn()
{
    return !empty(unserialize($_SESSION['user']));
}

?>
