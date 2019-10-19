<?php
if(php_sapi_name() != 'cli')
    header("HTTP/1.0 401");
else
{
    $bypassCoreCheck = true;
    require_once("../config.php");
    require_once(ROOT_DIR . '/classes/tables/core/Demos.php');

    $demos = Demos::getObjects();
    $demoSize = sizeof($demos);
    for($i = 0; $i < $demoSize; $i++)
    {
        $demo = $demos[$i];
        $percent = round($i / $demoSize, 2) * 100;

        if (strtotime($demo->Expires) >= strtotime(date()))
        {
            echo "$i / {$demoSize} - {$percent}% - Deleting demo with expiration {$demo->Expires()}...\n";

            $account = Accounts::withId($demo->AccountId);

            $db = new Database($account->DbId);

            $mediaDir = $db->query('SELECT * FROM ! WHERE ! = ?', ['config', 'key'], ['TEAM_ROBOT_MEDIA_DIR'])[0]['Value'];

            if (is_dir(ROOT_DIR . '/assets/robot-media/originals/' . $mediaDir))
                rmdir(ROOT_DIR . '/assets/robot-media/originals/' . $mediaDir);

            if (is_dir(ROOT_DIR . '/assets/robot-media/originals/' . $mediaDir))
                rmdir(ROOT_DIR . '/assets/robot-media/thumbs/' . $mediaDir);

            $account->delete();
            $demo->delete();

            $db->query('DROP DATABASE !', [$account->DbId], array());
        }
    }
}
?>