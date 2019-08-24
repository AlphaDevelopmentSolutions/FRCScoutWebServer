<?php
require_once("../config.php");

unset($_SESSION['user']);

$url = $_POST['url'];

if(!empty($url))
    header('Location: ' . URL_PATH . $url);

else
    header('Location: ' . URL_PATH);
?>