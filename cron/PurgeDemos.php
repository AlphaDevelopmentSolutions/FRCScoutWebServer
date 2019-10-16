<?php
$bypassCoreCheck = true;
require_once("../config.php");
require_once(ROOT_DIR . '/classes/tables/core/Demos.php');

foreach(Demos::getObjects() as $demo)
{
    if(strtotime($demo->Expires) >= strtotime(date()))
    {
        $account = Accounts::withId($demo->AccountId);

        $db = new Database($account->DbId);

        $mediaDir = $db->query('SELECT * FROM ! WHERE ! = ?', ['config', 'key'], ['TEAM_ROBOT_MEDIA_DIR'])[0]['Value'];

        if(is_dir(ROOT_DIR . '/assets/robot-media/originals/' . $mediaDir))
            rmdir(ROOT_DIR . '/assets/robot-media/originals/' . $mediaDir);

        if(is_dir(ROOT_DIR . '/assets/robot-media/originals/' . $mediaDir))
            rmdir(ROOT_DIR . '/assets/robot-media/thumbs/' . $mediaDir);

        $account->delete();
        $demo->delete();

        $db->query('DROP DATABASE !', [$account->DbId], array());
    }
}

?>