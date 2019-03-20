<?php

class Matches
{

    public static function getMatchIds($eventId)
    {
        $database = new Database();
        $matchIds = $database->query(
            "SELECT 
                      MatchId 
                    FROM 
                      scout_cards 
                    WHERE
                      EventId = " . $database->quote($eventId) .
                    "GROUP BY
                      MatchId;"
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

    public static function getMatchBlueAllianceScore($eventId, $matchId)
    {
        $database = new Database();
        $blueAllianceScores = $database->query(
            "SELECT 
                      AVG(BlueAllianceFinalScore) AS BlueAllianceScore
                    FROM 
                      scout_cards 
                    WHERE
                      EventId = " . $database->quote($eventId) .
                    "AND 
                      MatchId = " . $database->quote($matchId)
        );
        $database->close();

        $response = array();

        if($blueAllianceScores && $blueAllianceScores->num_rows > 0)
        {
            while ($row = $blueAllianceScores->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public static function getMatchRedAllianceScore($eventId, $matchId)
    {
        $database = new Database();
        $redAllianceFinalScores = $database->query(
            "SELECT 
                      AVG(RedAllianceFinalScore) AS RedAllianceScore
                    FROM 
                      scout_cards 
                    WHERE
                      EventId = " . $database->quote($eventId) .
                    "AND 
                      MatchId = " . $database->quote($matchId)
        );
        $database->close();


        $response = array();

        if($redAllianceFinalScores && $redAllianceFinalScores->num_rows > 0)
        {
            while ($row = $redAllianceFinalScores->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public static function getBlueAllianceScoutCardIds($eventId, $matchId)
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
                      AllianceColor = " . $database->quote('BLUE')
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

}

?>