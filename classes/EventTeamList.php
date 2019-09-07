<?php

class EventTeamList
{
    public $Id;
    public $TeamId;
    public $EventId;

    private static $TABLE_NAME = 'event_team_list';

    function save()
    {
        $database = new Database();
        $sql = 'INSERT INTO ' . EventTeamList::$TABLE_NAME . ' 
                                  (
                                  TeamId,
                                  EventId
                                  )
                                  VALUES 
                                  (
                                  ' . ((empty($this->TeamId)) ? 'NULL' : $database->quote($this->TeamId)) .',
                                  ' . ((empty($this->EventId)) ? 'NULL' : $database->quote($this->EventId)) .'
                                  );';
        if($database->query($sql))
        {
            $database->close();

            return true;
        }
        $database->close();
        return false;
    }
}

?>