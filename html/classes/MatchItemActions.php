<?php

class MatchItemActions
{
    public $Id;
    public $ScoutCardId;
    public $MatchState;
    public $ItemType;
    public $Action;

    private static $TABLE_NAME = 'match_item_actions';

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

        if(empty($this->Id))
        {
            $sql = 'INSERT INTO ' . self::$TABLE_NAME . ' 
                                      (
                                      ScoutCardId,
                                      MatchState,
                                      ItemType,
                                      `Action`
                                      )
                                      VALUES 
                                      (
                                      ' . ((empty($this->ScoutCardId)) ? 'NULL' : $database->quote($this->ScoutCardId)) .',
                                      ' . ((empty($this->MatchState)) ? 'NULL' : $database->quote($this->MatchState)) .',
                                      ' . ((empty($this->ItemType)) ? 'NULL' : $database->quote($this->ItemType)) .',
                                      ' . ((empty($this->Action)) ? 'NULL' : $database->quote($this->Action)) .'
                                      );';

            if($database->query($sql))
            {
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
            ScoutCardId = " . ((empty($this->MatchId)) ? "NULL" : $database->quote($this->MatchId)) .", 
            MatchState = " . ((empty($this->TeamId)) ? "NULL" : $database->quote($this->TeamId)) .", 
            ItemType = " . ((empty($this->EventId)) ? "NULL" : $database->quote($this->EventId)) .", 
            `Action` = " . ((empty($this->CompletedDate)) ? "NULL" : $database->quote($this->CompletedDate)) ."
            WHERE (Id = " . $database->quote($this->Id) . ");";

            if($database->query($sql))
            {
                $database->close();
                return true;
            }

            $database->close();
            return false;
        }
    }

    function delete()
    {
        if(empty($this->Id))
            return false;

        $database = new Database();
        $sql = 'DELETE FROM '.self::$TABLE_NAME.' WHERE '.'id = '.$database->quote($this->Id);
        $rs = $database->query($sql);

        if($rs)
            return true;


        return false;
    }

    /**
     * Returns all match actions associated with a scout card
     * @param $scoutCardId
     * @return array
     */
    public static function getMatchItemActionsForScoutCard($scoutCardId)
    {
        $database = new Database();
        $matchItemActions = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME ."  
                    WHERE 
                      ScoutCardId = " . $database->quote($scoutCardId)
        );
        $database->close();

        $response = array();

        if($matchItemActions && $matchItemActions->num_rows > 0)
        {
            while ($row = $matchItemActions->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }
}

?>