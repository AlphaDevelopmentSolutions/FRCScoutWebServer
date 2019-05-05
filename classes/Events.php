<?php

class Events extends Table
{
    public $Id;
    public $BlueAllianceId;
    public $Name;
    public $City;
    public $StateProvince;
    public $Country;
    public $StartDate;
    public $EndDate;

    protected static $TABLE_NAME = 'events';

    /**
     * Overrides parent withId method and provides a custom column name to use when loading
     * @param int|string $id
     * @return Events
     */
    public static function withId($id)
    {
        return parent::withId($id, 'BlueAllianceId');
    }


    /**
     * Gets all events in the database
     * @return Events[]
     */
    public static function getEvents()
    {
        $database = new Database();
        $events = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME .
                    " ORDER BY StartDate DESC "
        );
        $database->close();

        $response = array();

        if($events && $events->num_rows > 0)
        {
            while ($row = $events->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    /**
     * @param null | Matches $match
     * @param null | Teams $team
     * @return Teams[]
     */
    public function getTeams($match = null, $team = null)
    {
        $database = new Database();

        $sql = "SELECT
                      *
                    FROM
                      teams
                    WHERE
                      Id IN
                      (
                        SELECT
                          TeamId
                        FROM
                          event_team_list
                        WHERE EventId = " . $database->quote($this->BlueAllianceId) . ")";

        if(!empty($match))
            $sql .= ' AND Id = ' . $database->quote($team->Id);

        if(!empty($team))
        {
            $sql .= ' AND (Id IN (SELECT BlueAllianceTeamOneId FROM matches WHERE ' . $database->quoteColumn('Key') . ' = ' . $match->Key . ')';
            $sql .= ' OR Id IN (SELECT BlueAllianceTeamTwoId FROM matches WHERE ' . $database->quoteColumn('Key') . ' = ' . $match->Key . ')';
            $sql .= ' OR Id IN (SELECT BlueAllianceTeamThreeId FROM matches WHERE ' . $database->quoteColumn('Key') . ' = ' . $match->Key . ')';

            $sql .= ' OR Id IN (SELECT RedAllianceTeamOneId FROM matches WHERE ' . $database->quoteColumn('Key') . ' = ' . $match->Key . ')';
            $sql .= ' OR Id IN (SELECT RedAllianceTeamTwoId FROM matches WHERE ' . $database->quoteColumn('Key') . ' = ' . $match->Key . ')';
            $sql .= ' OR Id IN (SELECT RedAllianceTeamThreeId FROM matches WHERE ' . $database->quoteColumn('Key') . ' = ' . $match->Key . '))';
        }



        $teams = $database->query($sql);
        $database->close();

        $response = array();

        if($teams && $teams->num_rows > 0)
        {
            while ($row = $teams->fetch_assoc())
            {
                $response[] = Teams::withProperties($row);
            }
        }

        return $response;
    }

    /**
     * @param null $match
     * @param null $team
     * @return Matches[]
     */
    public function getMatches($match = null, $team = null)
    {
        $database = new Database();

        $sql = "SELECT 
                      * 
                    FROM 
                      matches 
                    WHERE
                      EventId = " . $database->quote($this->BlueAllianceId) .
            " AND MatchType = " . $database->quote(Matches::$MATCH_TYPE_QUALIFICATIONS);

        //add the team query if a team was specified
        if(!empty($team))
            $sql .= " AND " . $database->quote($team->Id) . " IN (BlueAllianceTeamOneId, BlueAllianceTeamTwoId, BlueAllianceTeamThreeId, RedAllianceTeamOneId, RedAllianceTeamTwoId, RedAllianceTeamThreeId)";

        //add the team query if a team was specified
        if(!empty($match))
            $sql .= " AND " . $database->quoteColumn('Key') . " = " . $database->quote($match->Key);


        $sql .= "ORDER BY MatchNumber DESC";


        $matchIds = $database->query($sql);
        $database->close();

        $response = array();

        if($matchIds && $matchIds->num_rows > 0)
        {
            while ($row = $matchIds->fetch_assoc())
            {
                $response[] = Matches::withProperties($row);
            }
        }

        return $response;
    }

    /**
     * @param Matches $match
     * @param Teams $team
     * @param ScoutCards $scoutCard
     * @return ScoutCards[]
     */
    public function getScoutCards($match = null, $team = null, $scoutCard = null)
    {
        $database = new Database();

        $sql = "SELECT 
                      * 
                    FROM 
                      scout_cards 
                    WHERE
                      EventId = " . $database->quote($this->BlueAllianceId);

        //add the team query if a team was specified
        if(!empty($team))
            $sql .= " AND " . $database->quoteColumn('TeamId') . " = " . $database->quote($team->Id);

        //add the match query if a team was specified
        if(!empty($match))
            $sql .= " AND " . $database->quoteColumn('Key') . " = " . $database->quote($match->Key);

        //add the scout card query if a team was specified
        if(!empty($scoutCard))
            $sql .= " AND " . $database->quoteColumn('Id') . " = " . $database->quote($scoutCard->Id);


        $sql .= "ORDER BY Id DESC";

        $scoutCards = $database->query($sql);
        $database->close();

        $response = array();

        if($scoutCards && $scoutCards->num_rows > 0)
        {
            while ($row = $scoutCards->fetch_assoc())
            {
                $response[] = ScoutCards::withProperties($row);
            }
        }

        return $response;
    }

    /**
     * @param Teams $team
     * @param PitCards $pitCard
     * @return PitCards[]
     */
    public function getPitCards($team = null, $pitCard = null)
    {
        $database = new Database();

        $sql = "SELECT 
                      * 
                    FROM 
                      pit_cards";

        //add the team query if a team was specified
        if(!empty($team))
            $sql .= " AND " . $database->quoteColumn('TeamId') . " = " . $database->quote($team->Id);

        //add the scout card query if a team was specified
        if(!empty($pitCard))
            $sql .= " AND " . $database->quoteColumn('Id') . " = " . $database->quote($pitCard->Id);

        $sql .= " ORDER BY Id DESC";

        $scoutCards = $database->query($sql);
        $database->close();

        $response = array();

        if($scoutCards && $scoutCards->num_rows > 0)
        {
            while ($row = $scoutCards->fetch_assoc())
            {
                $response[] = PitCards::withProperties($row);
            }
        }

        return $response;
    }

    public function toHtml()
    {
        // TODO: Implement toHtml() method.
    }

    public function toString()
    {
        // TODO: Implement toString() method.
    }

}

?>