<?php

class Matches extends Table
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

    protected static $TABLE_NAME = 'matches';

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
     * Gets scout cards for a specific match
     * @param null | Teams $team if specified, filters by team
     * @param null | ScoutCards $scoutCard if specified, filters by scoutcard
     * @return ScoutCards[]
     */
    public function getScoutCards($team = null, $scoutCard = null)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = 'scout_cards';
        $cols[] = 'MatchId';
        $args[] = $this->Key;

        //if team specified, filter by team
        if(!empty($team))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        //if scoutcard specified, filter by scoutcard
        if(!empty($scoutCard))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'Id';
            $args[] = $scoutCard->Id;
        }

        $sql .= " ORDER BY ! DESC";
        $cols[] = 'Id';

        $rows = self::query($sql, $cols, $args);

        foreach ($rows as $row)
            $response[] = ScoutCards::withProperties($row);

        return $response;
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
                    return 'Qualification';
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

        return 'Qualification';
    }

    /**
     * Returns the final string to be displayed when referencing a match
     * @return string
     */
    public function toString()
    {
        return $this->getMatchTypeString() . ' ' . $this->MatchNumber;
    }

    /**
     * Returns the html for displaying a match card
     * @param string $buttonHref href action when clicking the button
     * @param string $buttonText button text to display
     * @param int | null $teamId selected team
     * @return string html to display
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
                                        <td><a href="/team-matches.php?teamId=' . $this->BlueAllianceTeamOneId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->BlueAllianceTeamOneId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->BlueAllianceTeamOneId . '</a></td>
                                        <td><a href="/team-matches.php?teamId=' . $this->BlueAllianceTeamTwoId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->BlueAllianceTeamTwoId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->BlueAllianceTeamTwoId . '</a></td>
                                        <td><a href="/team-matches.php?teamId=' . $this->BlueAllianceTeamThreeId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->BlueAllianceTeamThreeId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->BlueAllianceTeamThreeId . '</a></td>
                                        <td><span ' . (($this->BlueAllianceScore > $this->RedAllianceScore) ? 'style="font-weight: bold;"' : 'style="font-weight: 300;"') . '>' . $this->BlueAllianceScore . '</span></td>
                                    </tr>
                                    <tr class="red-alliance-bg">
                                        <td><a href="/team-matches.php?teamId=' . $this->RedAllianceTeamOneId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->RedAllianceTeamOneId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamOneId . '</a></td>
                                        <td><a href="/team-matches.php?teamId=' . $this->RedAllianceTeamTwoId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->RedAllianceTeamTwoId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamTwoId . '</a></td>
                                        <td><a href="/team-matches.php?teamId=' . $this->RedAllianceTeamThreeId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->RedAllianceTeamThreeId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamThreeId . '</a></td>
                                        <td><span ' . (($this->BlueAllianceScore < $this->RedAllianceScore) ? 'style="font-weight: bold;"' : 'style="font-weight: 300;"') . '>' . $this->RedAllianceScore . '</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-card__actions">
                        <a href="' . $buttonHref . '" class="mdl-button">' . $buttonText . '</a>
                    </div>
                </div>
            </section>
        </div>';

        return $html;
    }

}

?>