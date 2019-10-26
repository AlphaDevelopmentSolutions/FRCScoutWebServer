<?php
$bypassCoreCheck = true;
require_once("../config.php");
require_once(ROOT_DIR . "/classes/Ajax.php");

$ajax = new Ajax();

switch ($_POST['action'])
{
    case 'api_key':

        $apiKey = "";

        foreach(str_split(sprintf( '%04x%04x%04x%04x%04x%04x%04x%04x%04x%04x%04x%04x%04x',
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ))) as $char)
        {
            if(ctype_alpha($char) && mt_rand(0, 10000) % 2 == 0)
                $char = strtoupper($char);
            $apiKey .= $char;
        }

        $ajax->success($apiKey);

        break;
}