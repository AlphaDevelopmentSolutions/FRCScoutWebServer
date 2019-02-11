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

    function save()
    {
        $database = new Database();
        $sql = 'INSERT INTO ' . Teams::$TABLE_NAME . ' 
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
                                  ' . ((empty($this->Id)) ? 'NULL' : $database->quote($this->Id)) .',
                                  ' . ((empty($this->Name)) ? 'NULL' : $database->quote($this->Name)) .',
                                  ' . ((empty($this->City)) ? 'NULL' : $database->quote($this->City)) .',
                                  ' . ((empty($this->StateProvince)) ? 'NULL' : $database->quote($this->StateProvince)) .',
                                  ' . ((empty($this->Country)) ? 'NULL' : $database->quote($this->Country)) .',
                                  ' . ((empty($this->RookieYear)) ? 'NULL' : $database->quote($this->RookieYear)) .',
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

    public static function getTeamsForEvent($eventId)
    {
        $database = new Database();
        $teams = $database->query(
            "SELECT 
                      * 
                    FROM 
                      teams 
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

}

?>