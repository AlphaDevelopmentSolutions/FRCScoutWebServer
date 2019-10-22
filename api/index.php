<?php
$bypassCoreCheck = true;
require_once('../config.php');
require_once(ROOT_DIR . '/classes/Api.php');

$api = new Api();
$api->success("Hello Good Sir!");

?>
