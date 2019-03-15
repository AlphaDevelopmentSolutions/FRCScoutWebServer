<?php
require_once("config.php");

session_destroy();

$url = $_POST['url'];

if(!empty($url))
    header('Location: http://scouting.wiredcats5885.ca' . $url);

else
    header('Location: http://scouting.wiredcats5885.ca');
?>