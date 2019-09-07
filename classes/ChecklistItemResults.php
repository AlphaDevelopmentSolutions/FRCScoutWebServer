<?php

class ChecklistItemResults extends Table implements Status
{
    public $Id;
    public $ChecklistItemId;
    public $MatchId;
    public $Status;
    public $CompletedBy;
    public $CompletedDate;

    protected static $TABLE_NAME = 'checklist_item_results';

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

    public function toString()
    {
        // TODO: Implement toString() method.
    }

}

interface Status
{
    const COMPLETE = 'COMPLETE';
    const INCOMPLETE = 'INCOMPLETE';
}

?>