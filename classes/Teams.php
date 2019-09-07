<?php

class Teams extends Table
{
    public $Id;
    public $Name;
    public $City;
    public $StateProvince;
    public $Country;
    public $RookieYear;
    public $FacebookURL;
    public $TwitterURL;
    public $InstagramURL;
    public $YoutubeURL;
    public $WebsiteURL;
    public $ImageFileURI;

    protected static $TABLE_NAME = 'teams';


    public static function getTeamsAtEvent($eventId)
    {
        $database = new Database();
        $teams = $database->query(
            "SELECT
                      *
                    FROM
                      " . self::$TABLE_NAME ."
                    WHERE
                      id IN
                      (
                        SELECT
                          TeamId
                        FROM
                          event_team_list
                        WHERE EventId = " . $database->quote($eventId) . ")"
        );
        $database->close();

        $response = array();

        if($teams && $teams->num_rows > 0)
        {
            while ($row = $teams->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;

    }

    public static function getAllianceTeamsForMatch($eventId, Matches $match, $allianceColor)
    {
        $database = new Database();
        $teams = $database->query(
            "SELECT
                      TeamId
                    FROM
                      scout_cards
                    WHERE
                      EventId = " . $database->quote($eventId) . "
                    AND 
                      MatchId = " . $match->MatchNumber . "
                    AND 
                      MatchType = " . $database->quote($match->MatchType) . "
                    AND 
                      SetNumber = " . $match->SetNumber . "
                    AND
                      AllianceColor = " . $database->quote($allianceColor)
        );
        $database->close();

        $response = array();

        if($teams && $teams->num_rows > 0)
        {
            while ($row = $teams->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;

    }

    /**
     * Returns the URI of the teams profile image
     */
    public static function getProfileImageUri($teamId)
    {
        if(!empty($teamId))
        {
            $database = new Database();
            $robotMedia = $database->query(
                "SELECT
                      FileURI
                    FROM
                      robot_media
                    WHERE
                      TeamId = " . $teamId . "
                    ORDER BY Id DESC LIMIT 1"
            );
            $database->close();


            if($robotMedia && $robotMedia->num_rows > 0)
            {
                while ($row = $robotMedia->fetch_assoc())
                {
                    return $row['FileURI'];
                }
            }
        }
    }

    public static function getRobotPhotos($teamId)
    {
        if(!empty($teamId))
        {
            $response = array();
            $database = new Database();
            $robotMedia = $database->query(
                "SELECT
                      FileURI
                    FROM
                      robot_media
                    WHERE
                      TeamId = " . $teamId . "
                    ORDER BY Id DESC"
            );
            $database->close();


            if($robotMedia && $robotMedia->num_rows > 0)
            {
                while ($row = $robotMedia->fetch_assoc())
                {
                    $response[] = $row['FileURI'];
                }
            }

            return $response;
        }

        return array();
    }

    public function toHtml()
    {
        // TODO: Implement toHtml() method.
    }

    /**
     * Formats the team name for string use
     * EX: 5885 - Villanova WiredCats
     * @return string
     */
    public function toString()
    {
        return $this->Id . ' - ' . $this->Name;
    }
}

?>