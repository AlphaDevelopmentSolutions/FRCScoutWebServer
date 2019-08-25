<?php
require_once("../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
switch ($_POST['action'])
{
    case 'load_team_list':
        $return_array = array();

        $number = $_POST['number'];

        foreach(Teams::getObjects('Id', 'DESC') as $team)
        {
            $return_array[] = [
                label => "$team->Id - $team->Name",
                number => "$team->Id",
                name => "$team->Name"
            ];
        }


        sort($return_array);
        echo json_encode($return_array);

        break;
}