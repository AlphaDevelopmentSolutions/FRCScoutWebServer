<?php

class RobotInfoKeys
{
    /**
     * Returns the list of keys used in the 2019 season
     * @param RobotInfoKeyStates | null $state if specified, filters by state (autonomous / teleop)
     * @return String[]
     */
    private static function getRobotInfoKeys2019($state = null)
    {
        switch($state)
        {
            case RobotInfoKeyStates::Autonomous:
                $returnArray = ['AutoExitHabitat', 'AutoHatch', 'AutoCargo'];
                break;

            case RobotInfoKeyStates::Teleop:
                $returnArray = ['TeleopHatch', 'TeleopCargo'];
                break;

            case RobotInfoKeyStates::EndGame:
                $returnArray = ['ReturnToHabitat'];
                break;

            default:
                $returnArray = (new ReflectionClass('RobotInfoKeys2019'))->getConstants();
                break;
        }

        return array_merge(self::getRobotInfoKeysBase(), $returnArray);
    }

    /**
     * Returns the list of keys that will be used every season
     * @return String[]
     */
    private static function getRobotInfoKeysBase()
    {
        return (new ReflectionClass('RobotInfoKeysBase'))->getConstants();
    }

    /**
     * Returns the list of states for a robot info item
     * @return String[]
     */
    public static function getRobotInfoKeyStates()
    {
        return (new ReflectionClass('RobotInfoKeyStates'))->getConstants();
    }

    /**
     * Logic for deciding which keyset to return
     * @param Years | null $year
     * @param Events | null $event
     * @param RobotInfoKeyStates | null $state if specified, filters by state (autonomous / teleop)
     * @return String[]
     */
    public static function getRobotInfoKeys($year = null, $event = null, $state = null)
    {
        $yearId = ((!empty($year)) ? $year->Id : ((!empty($event)) ? $event->YearId : date('Y')));

        switch($yearId)
        {
            case '2019':
                return RobotInfoKeys::getRobotInfoKeys2019($state);
                break;

            default:
                return RobotInfoKeys::getRobotInfoKeys2019($state);
                break;
        }
    }
}


//keys that are to be used in 2019
class RobotInfoKeys2019
{
    const AutoExitHabitat = 'Auto Exit Habitat';
    const AutoHatch = 'Auto Hatch';
    const AutoCargo = 'Auto Cargo';

    const TeleopHatch = 'Teleop Hatch';
    const TeleopCargo = 'Teleop Cargo';

    const ReturnToHabitat = 'Return To Habitat';
}

//keys that are to be used as base keys all the time
interface RobotInfoKeysBase
{
    const Drivetrain = 'Drivetrain';
    const RobotWeight = 'Robot Weight';
    const RobotLength = 'Robot Length';
    const RobotWidth = 'Robot Width';
    const RobotHeight = 'Robot Height';
    const Notes = 'Notes';

}

//state keys for various periods of a event
interface RobotInfoKeyStates
{
    const PreGame = 'Pre Game';
    const Autonomous = 'Autonomous';
    const Teleop = 'Teleop';
    const EndGame = 'End Game';
    const PostGame = 'Post Game';

}