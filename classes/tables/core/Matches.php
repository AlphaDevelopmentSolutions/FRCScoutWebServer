<?php

class Matches extends CoreTable
{

    public $Id;
    public $Date;
    public $EventId;
    public $MatchType;
    public $Key;
    public $MatchNumber;
    public $SetNumber;
    public $BlueAllianceTeamOneId;
    public $BlueAllianceTeamTwoId;
    public $BlueAllianceTeamThreeId;
    public $RedAllianceTeamOneId;
    public $RedAllianceTeamTwoId;
    public $RedAllianceTeamThreeId;
    public $BlueAllianceScore;
    public $RedAllianceScore;

    public static $TABLE_NAME = 'matches';

    static $MATCH_TYPE_QUALIFICATIONS = 'qm';
    static $MATCH_TYPE_QUARTER_FINALS = 'qf';
    static $MATCH_TYPE_SEMI_FINALS = 'sf';
    static $MATCH_TYPE_FINALS = 'f';

    /**
     * Overrides parent withId method and provides a custom column name to use when loading
     * @param CoreDatabase $database
     * @param int|string $id
     * @return Matches
     */
    public static function withId($database, $id)
    {
        return parent::withId($database, $id, 'Key');
    }

    /**
     * Retrieves objects from the database
     * @param CoreDatabase $database
     * @param Events | null $event if specified, filters by id
     * @param Teams | null $team if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return Matches[]
     */
    public static function getObjects($database, $event = null, $team = null, $orderBy = 'Id', $orderDirection = 'DESC')
    {
        $whereStatment = "";
        $cols = array();
        $args = array();

        //if year specified, filter by event
        if(!empty($event))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'EventId';
            $args[] = $event->BlueAllianceId;
        }

        //if team specified, filter by team
        if(!empty($team))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ? IN (!, !, !, !, !, !) ";

            $cols[] = 'BlueAllianceTeamOneId';
            $cols[] = 'BlueAllianceTeamTwoId';
            $cols[] = 'BlueAllianceTeamThreeId';
            $cols[] = 'RedAllianceTeamOneId';
            $cols[] = 'RedAllianceTeamTwoId';
            $cols[] = 'RedAllianceTeamThreeId';
            $args[] = $team->Id;
        }

        return parent::getObjects($database, $whereStatment, $cols, $args, $orderBy, $orderDirection);
    }

    /**
     * Gets and returns the color of the team specified in a match
     * @param Teams $team
     * @return string
     */
    public function getAllianceColor($team)
    {
       if($team->Id == $this->BlueAllianceTeamOneId
            || $team->Id == $this->BlueAllianceTeamTwoId
            || $team->Id == $this->BlueAllianceTeamThreeId)
            return AllianceColors::BLUE;

        else
            return AllianceColors::RED;
    }

    /**
     * Gets the match type from the object and returns it in string format
     * @return string
     */
    public function getMatchTypeString()
    {
        if(!empty($this->MatchType))
        {
            switch($this->MatchType)
            {
                case self::$MATCH_TYPE_QUALIFICATIONS:
                    return 'Quals';
                    break;

                case self::$MATCH_TYPE_QUARTER_FINALS:
                    return 'Quarter-Finals';
                    break;

                case self::$MATCH_TYPE_SEMI_FINALS:
                    return 'Semi-Finals';
                    break;

                case self::$MATCH_TYPE_FINALS:
                    return 'Finals';
                    break;

                default:
                    return 'Qualification';
                    break;

            }
        }

        return 'Quals';
    }

    /**
     * Gets stats for the specified match
     * @param $matches Matches[] to iterate through
     * @param $scoutCardInfoKeys ScoutCardInfoKeys[] to iterate through
     * @param $scoutCardInfos ScoutCardInfo[] to iterate through
     * @return array of data calculated
     */
    public function getStats($matches, $scoutCardInfoKeys, $scoutCardInfos)
    {
        $matchTeamList = array();
        $matchStatsArray = array();
        $matchCardArray = array();

        foreach ($matches as $storedMatch)
        {
            $matchTeamList[] = $storedMatch->BlueAllianceTeamOneId;
            $matchTeamList[] = $storedMatch->BlueAllianceTeamTwoId;
            $matchTeamList[] = $storedMatch->BlueAllianceTeamThreeId;

            $matchTeamList[] = $storedMatch->RedAllianceTeamOneId;
            $matchTeamList[] = $storedMatch->RedAllianceTeamTwoId;
            $matchTeamList[] = $storedMatch->RedAllianceTeamThreeId;
        }

        $filteredScoutCardInfos = array();

        foreach ($scoutCardInfos as $scoutCardInfo)
        {
            if ($scoutCardInfo->MatchId == $this->Key && in_array($scoutCardInfo->TeamId, $matchTeamList))
                $filteredScoutCardInfos[] = $scoutCardInfo;
        }

        foreach ($scoutCardInfoKeys as $scoutCardInfoKey)
        {
            $arrayKey = $scoutCardInfoKey->KeyState . ' ' . $scoutCardInfoKey->KeyName;
            
            if ($scoutCardInfoKey->IncludeInStats == '1')
            {
                if (!empty($filteredScoutCardInfos))
                {
                    foreach ($filteredScoutCardInfos as $scoutCardInfo)
                    {
                        if ($scoutCardInfo->PropertyKeyId == $scoutCardInfoKey->Id)
                        {
                            $matchStatsArray[$arrayKey] = ((!empty($matchStatsArray[$arrayKey])) ? $matchStatsArray[$arrayKey] + $scoutCardInfo->PropertyValue : $scoutCardInfo->PropertyValue);

                            $tempCardTotal = ((!empty($matchCardArray[$arrayKey])) ? $matchCardArray[$arrayKey] : 0);
                            $matchCardArray[$arrayKey] = (($scoutCardInfoKey->NullZeros == 1 && $scoutCardInfo->PropertyValue == 0) ? $tempCardTotal : $tempCardTotal + 1);
                        }
                    }

                    if (empty($matchStatsArray[$arrayKey]))
                    {
                        $matchStatsArray[$arrayKey] = 0;

                        $tempCardTotal = ((!empty($matchCardArray[$arrayKey])) ? $matchCardArray[$arrayKey] : 0);
                        $matchCardArray[$arrayKey] = (($scoutCardInfoKey->NullZeros == 1) ? $tempCardTotal : $tempCardTotal + 1);
                    }

                } else
                {
                    $matchStatsArray[$arrayKey] = 0;
                    $matchCardArray[$arrayKey] = 0;
                }
            }
        }

        foreach ($matchStatsArray as $key => $stat)
        {
            $tempCardCount = $matchCardArray[$key];

            $matchStatsArray[$key] = (($tempCardCount != 0) ? round($stat / $tempCardCount, 2) : 0);
        }

        return $matchStatsArray;
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->getMatchTypeString() . ' ' . $this->MatchNumber;
    }


    /**
     * Returns the object once converted into HTML
     * @param string $buttonHref href action when clicking the button
     * @param string $buttonText button text to display
     * @param int | null $teamId selected team
     * @return string
     */
    public function toHtml($buttonHref = null, $buttonText = null, $teamId = null)
    {
        ?>
        <div class="mdl-layout__tab-panel is-active" id="overview">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">
                    <div class="mdl-card__supporting-text">
                        <h4><?php echo $this->toString() ?></h4>
                        <div class="container">
                            <div class="row">
                                <table class="match-table">
                                    <tr class="blue-alliance-bg">
                                        <td><a href="<?php echo TEAMS_URL . 'match-list?teamId=' . $this->BlueAllianceTeamOneId . '&eventId=' . $this->EventId ?>" class="team-link <?php echo (($teamId == $this->BlueAllianceTeamOneId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") ?>"><?php echo $this->BlueAllianceTeamOneId ?></a></td>
                                        <td><a href="<?php echo TEAMS_URL . 'match-list?teamId=' . $this->BlueAllianceTeamTwoId . '&eventId=' . $this->EventId ?>" class="team-link <?php echo (($teamId == $this->BlueAllianceTeamTwoId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") ?>"></a><?php echo $this->BlueAllianceTeamTwoId ?></td>
                                        <td><a href="<?php echo TEAMS_URL . 'match-list?teamId=' . $this->BlueAllianceTeamThreeId . '&eventId=' . $this->EventId ?>" class="team-link <?php echo (($teamId == $this->BlueAllianceTeamThreeId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") ?>"><?php echo $this->BlueAllianceTeamThreeId ?></a></td>
                                        <td><span <?php echo (($this->BlueAllianceScore > $this->RedAllianceScore) ? 'style="font-weight: bold;"' : 'style="font-weight: 300;"') . '>' . $this->BlueAllianceScore ?></span></td>
                                    </tr>
                                    <tr class="red-alliance-bg">
                                        <td><a href="<?php echo TEAMS_URL . 'match-list?teamId=' . $this->RedAllianceTeamOneId . '&eventId=' . $this->EventId ?>" class="team-link <?php echo (($teamId == $this->RedAllianceTeamOneId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") ?>"><?php echo $this->RedAllianceTeamOneId ?></a></td>
                                        <td><a href="<?php echo TEAMS_URL . 'match-list?teamId=' . $this->RedAllianceTeamTwoId . '&eventId=' . $this->EventId ?>" class="team-link <?php echo (($teamId == $this->RedAllianceTeamTwoId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") ?>"><?php echo $this->RedAllianceTeamTwoId ?></a></td>
                                        <td><a href="<?php echo TEAMS_URL . 'match-list?teamId=' . $this->RedAllianceTeamThreeId . '&eventId=' . $this->EventId ?>" class="team-link <?php echo (($teamId == $this->RedAllianceTeamThreeId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") ?>"><?php echo $this->RedAllianceTeamThreeId ?></a></td>
                                        <td><span <?php echo (($this->BlueAllianceScore < $this->RedAllianceScore) ? 'style="font-weight: bold;"' : 'style="font-weight: 300;"') . '>' . $this->RedAllianceScore ?></span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-card__actions">
                        <a href="<?php echo $buttonHref ?>" class="mdl-button"><?php echo $buttonText ?></a>
                    </div>
                </div>
            </section>
        </div>
    <?php
    }
}

?>