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
     * @return new instance
     */
    static function withId($id)
    {
        $instance = new self();
        $instance->loadById($id);
        return $instance;

    }

    /**
     * Loads a new instance by specified properties
     * @param array $properties
     * @return new instance
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
     * @return new instance
     */
    protected function loadByProperties(Array $properties = array())
    {
        foreach($properties as $key => $value)
            $this->{$key} = $value;

    }

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return new instance
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
    }

}

?>