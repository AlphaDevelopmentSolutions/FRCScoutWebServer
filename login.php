<?php
require_once("config.php");

$username = $_POST['username'];
$password = $_POST['password'];
$url = $_POST['url'];

if(!empty($username) && !empty($password))
{
    $user = new Users();
    if($user->login($username, $password))
        $_SESSION['user'] = serialize($user);
}

if(!empty($url))
    header('Location: http://scouting.wiredcats5885.ca' . $url);

else
    header('Location: http://scouting.wiredcats5885.ca');
?>