<?php

class ChecklistItemResults implements Status
{
    public $Id;
    public $ChecklistItemId;
    public $MatchId;
    public $Status;
    public $CompletedBy;
    public $CompletedDate;

    private static $TABLE_NAME = 'checklist_item_results';


    /**
     * Loads a new instance by its database id
     * @param $id
     * @return ChecklistItemResults
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
     * @return ChecklistItemResults
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
     */
    protected function loadByProperties(Array $properties = array())
    {
        foreach($properties as $key => $value)
            $this->{$key} = $value;

    }

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return boolean
     */
    protected function loadById($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM ' . self::$TABLE_NAME . ' WHERE '.'Id = '.$database->quote($id);
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
                                        ChecklistItemId,
                                        MatchId,
                                        
                                        Status,
                                        CompletedBy,
                                        
                                        CompletedDate
                                      )
                                      VALUES 
                                      (
                                      ' . ((empty($this->ChecklistItemId)) ? '0' : $database->quote($this->ChecklistItemId)) .',                                      
                                      ' . ((empty($this->MatchId)) ? 'NULL' : $database->quote($this->MatchId)) .',          
                                                                  
                                      ' . ((empty($this->Status)) ? 'NULL' : $database->quote($this->Status)) .',                                      
                                      ' . ((empty($this->CompletedBy)) ? 'NULL' : $database->quote($this->CompletedBy)) .',                                      
                                      
                                      ' . ((empty($this->CompletedDate)) ? '2019-01-01 00:00:00' : $database->quote($this->CompletedDate)) .'
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
            ChecklistItemId = " . ((empty($this->ChecklistItemId)) ? "0" : $database->quote($this->ChecklistItemId)) .",             
            MatchId = " . ((empty($this->MatchId)) ? "NULL" : $database->quote($this->MatchId)) .",             
            
            Status = " . ((empty($this->Status)) ? "NULL" : $database->quote($this->Status)) .",             
            CompletedBy = " . ((empty($this->CompletedBy)) ? "NULL" : $database->quote($this->CompletedBy)) .",             
            
            CompletedDate = " . ((empty($this->CompletedDate)) ? "2019-01-01 00:00:00" : $database->quote($this->CompletedDate)) ."             
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

    /**
     * Gets all created checklist items
     * @param Matches | null $match gather only from specified match
     * @return array
     */
    public static function getChecklistItemResults($match = null)
    {
        $database = new Database();
        $sql = "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME;

        if(!is_null($match))
                $sql .= " WHERE MatchId = " . $database->quote($match->Key);

        $checklistItemResults = $database->query($sql);
        $database->close();

        $response = array();

        if($checklistItemResults && $checklistItemResults->num_rows > 0)
        {
            while ($row = $checklistItemResults->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    /**
     * Converts a completed checklist item to Html format, shown as a card
     * @return string
     */
    public function toHtml()
    {
        //get the checklist item
        $checklistItem = ChecklistItems::withId($this->ChecklistItemId);

        //create the status html with colors
        if($this->Status == Status::COMPLETE)
            $statusHtml = '<span class="good" style="font-weight: bold">' . Status::COMPLETE . '</span>';

        else if($this->Status == Status::INCOMPLETE)
            $statusHtml = '<span class="bad" style="font-weight: bold">' . Status::INCOMPLETE . '</span>';
        
        $html = 
            '<div class="mdl-layout__tab-panel is-active" id="overview">
                    <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                        <div class="mdl-card mdl-cell mdl-cell--12-col">
                            <div class="mdl-card__supporting-text">
                                <h4>' . $checklistItem->Title . '</h4>
                                ' . 'Current Status - ' . $statusHtml . '<br><br>
                                ' . $checklistItem->Description . '<br><br>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input disabled class="mdl-textfield__input" type="text" value="' . $this->CompletedBy . '">
                                    <label class="mdl-textfield__label" >Completed By</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input disabled class="mdl-textfield__input" type="text" value="' . $this->CompletedDate . '">
                                    <label class="mdl-textfield__label" >Completed On</label>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>';
        
        return $html;
        
    }

}

interface Status
{
    const COMPLETE = 'COMPLETE';
    const INCOMPLETE = 'INCOMPLETE';
}

?>