<?php
require_once("../config.php");

session_destroy();

$url = $_POST['url'];

if(!empty($url))
    header('Location: ' . URL_PATH . $url);

else
    header('Location: ' . URL_PATH);
?>