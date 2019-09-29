<?php

class ChecklistItems extends LocalTable
{
    public $Id;
    public $YearId;
    public $Title;
    public $Description;

    protected static $TABLE_NAME = 'checklist_items';

    /**
     * Retrieves objects from the database
     * @param Years | null $year if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return ChecklistItems[]
     */
    public static function getObjects($year = null, $orderBy = 'Title', $orderDirection = 'ASC')
    {
        $whereStatment = "";
        $cols = array();
        $args = array();

        //if year specified, filter by year
        if(!empty($year))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        return parent::getObjects($whereStatment, $cols, $args, $orderBy, $orderDirection);
    }

    /**
     * Override for the Table class delete function
     * Ensures all records associated with this key are deleted before deletion
     * @return bool
     */
    public function delete()
    {
        if(!empty($this->Id))
        {
            require_once(ROOT_DIR . '/classes/tables/local/ChecklistItemResults.php');

            //create the sql statement
            $sql = "DELETE FROM ! WHERE ! = ?";
            $cols[] = ChecklistItemResults::$TABLE_NAME;

            //Where
            $cols[] = 'ChecklistItemId';
            $args[] = $this->Id;

            self::deleteRecords($sql, $cols, $args);
        }

        return parent::delete();
    }

    /**
     * Gets the results for this checklist item
     * @param Matches | null $match if specified, filters by match
     * @return ChecklistItemResults[]
     */
    public function getResults($match = null)
    {
        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItemResults.php');

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = ChecklistItemResults::$TABLE_NAME;
        $cols[] = 'ChecklistItemId';
        $args[] = $this->Id;

        if(!empty($match))
        {
            $sql .= " AND ! = ? ";

            $cols[] = 'MatchId';
            $args[] = $match->Key;
        }

        $rows = self::queryRecords($sql, $cols, $args);

        foreach ($rows as $row)
            $response[] = ChecklistItemResults::withProperties($row);

        return $response;
    }

    /**
     * Returns the object once converted into HTML
     * @return string
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

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->Title;
    }

}

?>