<?php

class ChecklistItems extends Table
{
    public $Id;
    public $Title;
    public $Description;

    protected static $TABLE_NAME = 'checklist_items';

    /**
     * Gets all created checklist items
     * @return ChecklistItems[]
     */
    public static function getChecklistItems()
    {
        $database = new Database();
        $checklistItems = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME);
        $database->close();

        $response = array();

        if($checklistItems && $checklistItems->num_rows > 0)
        {
            while ($row = $checklistItems->fetch_assoc())
            {
                $response[] = ChecklistItems::withProperties($row);
            }
        }

        return $response;
    }

    /**
     * Gets the results for this checklist item
     * @param Matches | null $match if specified, filters by match
     * @return ChecklistItemResults[]
     */
    public function getResults($match = null)
    {
        $database = new Database();
        $sql = "SELECT 
                      * 
                    FROM 
                      checklist_item_results
                    WHERE 
                      ChecklistItemId = " . $database->quote($this->Id);

        if(!is_null($match))
            $sql .= " AND MatchId = " . $database->quote($match->Key);

        $checklistItemResults = $database->query($sql);
        $database->close();

        $response = array();

        if($checklistItemResults && $checklistItemResults->num_rows > 0)
        {
            while ($row = $checklistItemResults->fetch_assoc())
            {
                $response[] = ChecklistItemResults::withProperties($row);
            }
        }

        return $response;
    }

    /**
     * Converts a completed checklist item result to Html format, shown as a card
     * @return string HTML for displaying on the web page
     */
    public function toHtml()
    {
        $html =
            '<div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                        <div class="mdl-card__supporting-text">
                            <h4>' . $this->toString() . '</h4>
                            ' . $this->Description . '<br><br>
                        </div>
                    </div>
                </section>
            </div>';

        return $html;
    }

    public function toString()
    {
        return $this->Title;
    }

}

?>