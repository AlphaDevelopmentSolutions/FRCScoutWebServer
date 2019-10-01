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
     * @param int|string $id
     * @return Matches
     */
    public static function withId($id)
    {
        return parent::withId($id, 'Key');
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
     * Returns the amount of scout cards for a match
     * A scout card is defined as 1 team being scouted with multiple entries in the scout_card_info database
     * @return string
     */
    public function getScoutCardCount()
    {
        require_once(ROOT_DIR . '/interfaces/AllianceColors.php');

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ? GROUP BY !";
        $cols[] = ScoutCardInfo::$TABLE_NAME;

        $cols[] = 'EventId';
        $args[] = $this->EventId;

        $cols[] = 'MatchId';
        $args[] = $this->Key;

        $cols[] = 'TeamId';

        return count(self::queryRecords($sql, $cols, $args));
    }

    /**
     * Gets scout cards for a specific match
     * @param null | Teams $team if specified, filters by team
     * @return ScoutCardInfoArray[]
     */
    public function getScoutCards($team = null)
    {
        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');
        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfoArray.php');

        $response = new ScoutCardInfoArray();
        $scoutCardInfoArray = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = ScoutCardInfo::$TABLE_NAME;
        $cols[] = 'MatchId';
        $args[] = $this->Key;

        //if team specified, filter by team
        if(!empty($team))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        $sql .= " ORDER BY ! DESC";
        $cols[] = 'Id';

        $rows = self::queryRecords($sql, $cols, $args, LocalTable::$DB_NAME);

        foreach ($rows as $row)
            $response[] = ScoutCardInfo::withProperties($row);

        foreach($response as $key => $value)
            $scoutCardInfoArray[$value->TeamId][] = $response[$key];


        return $scoutCardInfoArray;
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
        $html = '
        <div class="mdl-layout__tab-panel is-active" id="overview">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">
                    <div class="mdl-card__supporting-text">
                        <h4>' . $this->toString() . '</h4>
                        <div class="container">
                            <div class="row">
                                <table class="match-table">
                                    <tr class="blue-alliance-bg">
                                        <td><a href="' . URL_PATH . TEAMS_URL . 'match-list?teamId=' . $this->BlueAllianceTeamOneId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->BlueAllianceTeamOneId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->BlueAllianceTeamOneId . '</a></td>
                                        <td><a href="' . URL_PATH . TEAMS_URL . 'match-list?teamId=' . $this->BlueAllianceTeamTwoId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->BlueAllianceTeamTwoId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->BlueAllianceTeamTwoId . '</a></td>
                                        <td><a href="' . URL_PATH . TEAMS_URL . 'match-list?teamId=' . $this->BlueAllianceTeamThreeId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->BlueAllianceTeamThreeId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->BlueAllianceTeamThreeId . '</a></td>
                                        <td><span ' . (($this->BlueAllianceScore > $this->RedAllianceScore) ? 'style="font-weight: bold;"' : 'style="font-weight: 300;"') . '>' . $this->BlueAllianceScore . '</span></td>
                                    </tr>
                                    <tr class="red-alliance-bg">
                                        <td><a href="' . URL_PATH . TEAMS_URL . 'match-list?teamId=' . $this->RedAllianceTeamOneId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->RedAllianceTeamOneId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamOneId . '</a></td>
                                        <td><a href="' . URL_PATH . TEAMS_URL . 'match-list?teamId=' . $this->RedAllianceTeamTwoId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->RedAllianceTeamTwoId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamTwoId . '</a></td>
                                        <td><a href="' . URL_PATH . TEAMS_URL . 'match-list?teamId=' . $this->RedAllianceTeamThreeId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->RedAllianceTeamThreeId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamThreeId . '</a></td>
                                        <td><span ' . (($this->BlueAllianceScore < $this->RedAllianceScore) ? 'style="font-weight: bold;"' : 'style="font-weight: 300;"') . '>' . $this->RedAllianceScore . '</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-card__actions">
                        <a href="' . URL_PATH . $buttonHref . '" class="mdl-button">' . $buttonText . '</a>
                    </div>
                </div>
            </section>
        </div>';

        return $html;
    }

}

?>