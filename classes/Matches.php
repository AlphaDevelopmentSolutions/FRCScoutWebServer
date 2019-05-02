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
     * Loads a new instance by its database key
     * @param $key
     * @return Matches
     */
    static function withKey($key)
    {
        $instance = new self();
        $instance->loadByKey($key);
        return $instance;

    }

    /**
     * Loads a new instance by its database key
     * @param $key
     */
    protected function loadByKey($key)
    {
        $database = new Database();
        $sql = 'SELECT * FROM ' . self::$TABLE_NAME . ' WHERE '.'`Key` = '.$database->quote($key);
        $rs = $database->query($sql);

        if($rs && $rs->num_rows > 0) {
            $row = $rs->fetch_assoc();

            if(is_array($row)) {
                foreach($row as $key => $value){
                    if(property_exists($this, $key)){
                        $this->$key = $value;
                    }
                }
            }
        }
    }


    /**
     * Gets all matches specified by the event
     * @param Events $event event to get matches from
     * @param Teams $team team to get matches for
     * @return array
     */
    public static function getMatches($event, $team = null)
    {
        $database = new Database();

        $sql = "SELECT 
                      * 
                    FROM 
                      matches 
                    WHERE
                      EventId = " . $database->quote($event->BlueAllianceId) .
            " AND MatchType = " . $database->quote(self::$MATCH_TYPE_QUALIFICATIONS);

        //add the team query if a team was specified
        if(!empty($team))
            $sql .= " AND " . $database->quote($team->Id) . " IN (BlueAllianceTeamOneId, BlueAllianceTeamTwoId, BlueAllianceTeamThreeId, RedAllianceTeamOneId, RedAllianceTeamTwoId, RedAllianceTeamThreeId)";

        $sql .= "ORDER BY MatchNumber DESC";


        $matchIds = $database->query($sql);
        $database->close();

        $response = array();

        if($matchIds && $matchIds->num_rows > 0)
        {
            while ($row = $matchIds->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public function getMatchScoutCardIds($eventId, $allianceColor)
    {
        $database = new Database();

        $sql = "SELECT 
                      Id
                    FROM 
                      scout_cards 
                    WHERE
                      EventId = " . $database->quote($eventId) .
                    "AND 
                      MatchId = " . $database->quote($this->Key) .
                    "AND 
                      AllianceColor = " . $database->quote($allianceColor);

        $teamIds = $database->query($sql);
        $database->close();


        $response = array();

        if($teamIds && $teamIds->num_rows > 0)
        {
            while ($row = $teamIds->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public static function getRedAllianceScoutCardIds($eventId, $matchId)
    {
        $database = new Database();
        $teamIds = $database->query(
            "SELECT 
                      Id
                    FROM 
                      scout_cards 
                    WHERE
                      EventId = " . $database->quote($eventId) .
                    "AND 
                      MatchId = " . $database->quote($matchId) .
                    "AND 
                      AllianceColor = " . $database->quote('RED')
        );
        $database->close();


        $response = array();

        if($teamIds && $teamIds->num_rows > 0)
        {
            while ($row = $teamIds->fetch_assoc())
            {
                $response[] = $row;
            }
        }

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
                                        <td><span ' . (($this->BlueAllianceScore > $this->RedAllianceScore) ? 'style="font-weight: bold;"' : "") . '>' . $this->BlueAllianceScore . '</span></td>
                                    </tr>
                                    <tr class="red-alliance-bg">
                                        <td><a href="/team-matches.php?teamId=' . $this->RedAllianceTeamOneId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->RedAllianceTeamOneId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamOneId . '</a></td>
                                        <td><a href="/team-matches.php?teamId=' . $this->RedAllianceTeamTwoId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->RedAllianceTeamTwoId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamTwoId . '</a></td>
                                        <td><a href="/team-matches.php?teamId=' . $this->RedAllianceTeamThreeId . '&eventId=' . $this->EventId .'" class="team-link ' . (($teamId == $this->RedAllianceTeamThreeId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamThreeId . '</a></td>
                                        <td><span ' . (($this->BlueAllianceScore < $this->RedAllianceScore) ? 'style="font-weight: bold;"' : "") . '>' . $this->RedAllianceScore . '</span></td>
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