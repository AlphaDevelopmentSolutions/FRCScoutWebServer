<?php

class Matches
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

    private static $TABLE_NAME = 'matches';

    static $MATCH_TYPE_QUALIFICATIONS = 'qm';
    static $MATCH_TYPE_QUARTER_FINALS = 'qf';
    static $MATCH_TYPE_SEMI_FINALS = 'sf';
    static $MATCH_TYPE_FINALS = 'f';

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return Matches
     */
    static function withId($id)
    {
        $instance = new self();
        $instance->loadById($id);
        return $instance;

    }

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
     * Loads a new instance by specified properties
     * @param array $properties
     * @return Matches
     */
    static function withProperties(Array $properties = array())
    {
        $instance = new self();
        $instance->loadByProperties($properties);
        return $instance;

    }

    /**
     * Loads a new instance by specified properties
     * @param array $properties
     * @return Matches
     */
    protected function loadByProperties(Array $properties = array())
    {
        foreach($properties as $key => $value)
            $this->{$key} = $value;

    }

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return Matches
     */
    protected function loadById($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM ' . self::$TABLE_NAME . ' WHERE '.'id = '.$database->quote($id);
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

            return true;
        }

        return false;
    }

    /**
     * Loads a new instance by its database key
     * @param $key
     * @return Matches
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

            return true;
        }

        return false;
    }

    function save()
    {

        $database = new Database();

        if (empty($this->Id))
        {
            $sql = 'INSERT INTO ' . self::$TABLE_NAME . '
                                  (
                                    `Date`,
                                    EventId,
                                    MatchType,
                                    `Key`,
                                    MatchNumber,
                                    SetNumber,
                                    BlueAllianceTeamOneId,
                                    BlueAllianceTeamTwoId,
                                    BlueAllianceTeamThreeId,
                                    RedAllianceTeamOneId,
                                    RedAllianceTeamTwoId,
                                    RedAllianceTeamThreeId,
                                    BlueAllianceScore,
                                    RedAllianceScore
                                  )
                                  VALUES
                                  (
                                  ' . ((empty($this->Date)) ? '2019-01-01 00:00:00' : $database->quote($this->Date)) . ',
                                  ' . ((empty($this->EventId)) ? 'NULL' : $database->quote($this->EventId)) . ',
                                  ' . ((empty($this->MatchType)) ? 'NULL' : $database->quote($this->MatchType)) . ',
                                  ' . ((empty($this->Key)) ? 'NULL' : $database->quote($this->Key)) . ',
                                  ' . ((empty($this->MatchNumber)) ? '0' : $database->quote($this->MatchNumber)) . ',
                                  ' . ((empty($this->SetNumber)) ? '0' : $database->quote($this->SetNumber)) . ',
                                  ' . ((empty($this->BlueAllianceTeamOneId)) ? '0' : $database->quote($this->BlueAllianceTeamOneId)) . ',
                                  ' . ((empty($this->BlueAllianceTeamTwoId)) ? '0' : $database->quote($this->BlueAllianceTeamTwoId)) . ',
                                  ' . ((empty($this->BlueAllianceTeamThreeId)) ? '0' : $database->quote($this->BlueAllianceTeamThreeId)) . ',
                                  ' . ((empty($this->RedAllianceTeamOneId)) ? '0' : $database->quote($this->RedAllianceTeamOneId)) . ',
                                  ' . ((empty($this->RedAllianceTeamTwoId)) ? '0' : $database->quote($this->RedAllianceTeamTwoId)) . ',
                                  ' . ((empty($this->RedAllianceTeamThreeId)) ? '0' : $database->quote($this->RedAllianceTeamThreeId)) . ',
                                  ' . ((empty($this->BlueAllianceScore)) ? '0' : $database->quote($this->BlueAllianceScore)) . ',
                                  ' . ((empty($this->RedAllianceScore)) ? '0' : $database->quote($this->RedAllianceScore)) . '
                                  );';

            if ($database->query($sql)) {
                $this->Id = $database->lastInsertedID();
                $database->close();

                return true;
            }
            $database->close();
            return false;

        }
        else
        {
            $sql = "UPDATE " . self::$TABLE_NAME . " SET
        `Date` = " . ((empty($this->Date)) ? "0" : $database->quote($this->Date)) . ",
        EventId = " . ((empty($this->EventId)) ? "0" : $database->quote($this->EventId)) . ",
        MatchType = " . ((empty($this->MatchType)) ? "0" : $database->quote($this->MatchType)) . ",
        `Key` = " . ((empty($this->Key)) ? "NULL" : $database->quote($this->Key)) . ",
        MatchNumber = " . ((empty($this->MatchNumber)) ? "0" : $database->quote($this->MatchNumber)) . ",
        SetNumber = " . ((empty($this->SetNumber)) ? "0" : $database->quote($this->SetNumber)) . ",
        BlueAllianceTeamOneId = " . ((empty($this->BlueAllianceTeamOneId)) ? "NULL" : $database->quote($this->BlueAllianceTeamOneId)) . ",
        BlueAllianceTeamTwoId = " . ((empty($this->BlueAllianceTeamTwoId)) ? "NULL" : $database->quote($this->BlueAllianceTeamTwoId)) . ",
        BlueAllianceTeamThreeId = " . ((empty($this->BlueAllianceTeamThreeId)) ? "NULL" : $database->quote($this->BlueAllianceTeamThreeId)) . ",
        RedAllianceTeamOneId = " . ((empty($this->RedAllianceTeamOneId)) ? "NULL" : $database->quote($this->RedAllianceTeamOneId)) . ",
        RedAllianceTeamTwoId = " . ((empty($this->RedAllianceTeamTwoId)) ? "NULL" : $database->quote($this->RedAllianceTeamTwoId)) . ",
        RedAllianceTeamThreeId = " . ((empty($this->RedAllianceTeamThreeId)) ? "NULL" : $database->quote($this->RedAllianceTeamThreeId)) . ",
        BlueAllianceScore = " . ((empty($this->BlueAllianceScore)) ? "0" : $database->quote($this->BlueAllianceScore)) . ",
        RedAllianceScore = " . ((empty($this->RedAllianceScore)) ? "0" : $database->quote($this->RedAllianceScore)) . "
        WHERE (Id = " . $database->quote($this->Id) . ");";

            if ($database->query($sql)) {
                $database->close();
                return true;
            }

            $database->close();
            return false;
        }

        return false;
    }


    public static function getMatches($eventId)
    {
        $database = new Database();
        $matchIds = $database->query(
            "SELECT 
                      * 
                    FROM 
                      matches 
                    WHERE
                      EventId = " . $database->quote($eventId) .
                    " AND MatchType = " . $database->quote(self::$MATCH_TYPE_QUALIFICATIONS) . "
                     ORDER BY MatchNumber DESC"
        );
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

    public static function getMatchesForTeam($eventId, $teamId)
    {
        $database = new Database();
        $sql = "SELECT 
                      * 
                    FROM 
                      matches 
                    WHERE
                      EventId = " . $database->quote($eventId) .
            " AND MatchType = " . $database->quote(self::$MATCH_TYPE_QUALIFICATIONS) .
            " AND " . $database->quote($teamId) . " IN (BlueAllianceTeamOneId, BlueAllianceTeamTwoId, BlueAllianceTeamThreeId, RedAllianceTeamOneId, RedAllianceTeamTwoId, RedAllianceTeamThreeId)
                     ORDER BY MatchNumber DESC";

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
        $teamIds = $database->query(
            "SELECT 
                      Id
                    FROM 
                      scout_cards 
                    WHERE
                      EventId = " . $database->quote($eventId) .
                    "AND 
                      MatchId = " . $database->quote($this->MatchNumber) .
                    "AND 
                      AllianceColor = " . $database->quote($allianceColor)
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
     * @param int $teamId selected team
     * @param int $scoutCardId used to change the view button
     * @return string html to display
     */
    public function toHtml($teamId = null, $scoutCardId = null)
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
                                        <td><span class="' . (($teamId == $this->BlueAllianceTeamOneId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->BlueAllianceTeamOneId . '</span></td>
                                        <td><span class="' . (($teamId == $this->BlueAllianceTeamTwoId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->BlueAllianceTeamTwoId . '</span></td>
                                        <td><span class="' . (($teamId == $this->BlueAllianceTeamThreeId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore > $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->BlueAllianceTeamThreeId . '</span></td>
                                        <td><span ' . (($this->BlueAllianceScore > $this->RedAllianceScore) ? 'style="font-weight: bold;"' : "") . '>' . $this->BlueAllianceScore . '</span></td>
                                    </tr>
                                    <tr class="red-alliance-bg">
                                        <td><span class="' . (($teamId == $this->RedAllianceTeamOneId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamOneId . '</span></td>
                                        <td><span class="' . (($teamId == $this->RedAllianceTeamTwoId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamTwoId . '</span></td>
                                        <td><span class="' . (($teamId == $this->RedAllianceTeamThreeId) ? " tr-selected-team " : "") . (($this->BlueAllianceScore < $this->RedAllianceScore) ? " tr-win " : "") . '">' . $this->RedAllianceTeamThreeId . '</span></td>
                                        <td><span ' . (($this->BlueAllianceScore < $this->RedAllianceScore) ? 'style="font-weight: bold;"' : "") . '>' . $this->RedAllianceScore . '</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-card__actions">' .
                        ((!is_null($scoutCardId)) ?
                            '<a href="/scout-card.php?scoutCardId=' . $scoutCardId . '" class="mdl-button">View Scout Card</a>'
                            :
                            '<a href="/match-overview-card.php?eventId=' . $this->EventId . '&matchId=' . $this->Id . '&allianceColor=BLUE" class="mdl-button">View Match Overview</a>'
                        ) .'
                    </div>
                </div>
            </section>
        </div>';

        return $html;
    }

}

?>