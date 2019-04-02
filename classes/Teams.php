<?php

class Teams
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

    private static $TABLE_NAME = 'teams';

    function load($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM '.self::$TABLE_NAME.' WHERE '.'id = '.$database->quote($id);
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
        $sql = 'INSERT INTO ' . self::$TABLE_NAME . ' 
                                  (
                                  Id,
                                  Name,
                                  City,
                                  StateProvince,
                                  Country,
                                  RookieYear,
                                  FacebookURL,
                                  TwitterURL,
                                  InstagramURL,
                                  YoutubeURL,
                                  WebsiteURL,
                                  ImageFileURI
                                  )
                                  VALUES 
                                  (
                                  ' . ((empty($this->Id)) ? '0' : $database->quote($this->Id)) .',
                                  ' . ((empty($this->Name)) ? 'NULL' : $database->quote($this->Name)) .',
                                  ' . ((empty($this->City)) ? 'NULL' : $database->quote($this->City)) .',
                                  ' . ((empty($this->StateProvince)) ? 'NULL' : $database->quote($this->StateProvince)) .',
                                  ' . ((empty($this->Country)) ? 'NULL' : $database->quote($this->Country)) .',
                                  ' . ((empty($this->RookieYear)) ? '0' : $database->quote($this->RookieYear)) .',
                                  ' . ((empty($this->FacebookURL)) ? 'NULL' : $database->quote($this->FacebookURL)) .',
                                  ' . ((empty($this->TwitterURL)) ? 'NULL' : $database->quote($this->TwitterURL)) .',
                                  ' . ((empty($this->InstagramURL)) ? 'NULL' : $database->quote($this->InstagramURL)) .',
                                  ' . ((empty($this->YoutubeURL)) ? 'NULL' : $database->quote($this->YoutubeURL)) .',
                                  ' . ((empty($this->WebsiteURL)) ? 'NULL' : $database->quote($this->WebsiteURL)) .',
                                  ' . ((empty($this->ImageFileURI)) ? 'NULL' : $database->quote($this->ImageFileURI)) .'
                                  );';
        if($database->query($sql))
        {
            $database->close();

            return true;
        }
        $database->close();
        return false;
    }

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

    public static function getAllianceTeamsForMatch($eventId, $matchId, $allianceColor)
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
                      MatchId = " . $matchId . "
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
}

?>