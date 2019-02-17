<?php

class Matches
{

    public static function getMatchIds($eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      MatchId 
                    FROM 
                      scout_cards 
                    GROUP BY
                      MatchId;"
        );
        $database->close();

        $response = array();

        if($scoutCards && $scoutCards->num_rows > 0)
        {
            while ($row = $scoutCards->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }


}

?>