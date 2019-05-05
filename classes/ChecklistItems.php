<?php

class ChecklistItems extends Table
{
    public $Id;
    public $Title;
    public $Description;

    protected static $TABLE_NAME = 'checklist_items';

    /**
     * Gets the results for this checklist item
     * @param Matches | null $match if specified, filters by match
     * @return ChecklistItemResults[]
     */
    public function getResults($match = null)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = 'checklist_item_results';
        $cols[] = 'ChecklistItemId';
        $args[] = $this->Id;

        if(!empty($match))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'MatchId';
            $args[] = $match->Key;
        }

        $rows = self::query($sql, $cols, $args);

        foreach ($rows as $row)
            $response[] = ChecklistItemResults::withProperties($row);

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