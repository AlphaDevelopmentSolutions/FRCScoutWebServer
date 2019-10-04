<?php

class ChecklistItemResults extends LocalTable implements Status
{
    public $Id;
    public $ChecklistItemId;
    public $MatchId;
    public $Status;
    public $CompletedBy;
    public $CompletedDate;

    public static $TABLE_NAME = 'checklist_item_results';

    /**
     * Retrieves objects from the database
     * @param Matches | null $match if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return ChecklistItemResults[]
     */
    public static function getObjects($match = null, $orderBy = 'Id', $orderDirection = 'ASC')
    {
        $whereStatment = "";
        $cols = array();
        $args = array();

        //if year specified, filter by year
        if(!empty($match))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'MatchId';
            $args[] = $match->Key;
        }

        return parent::getObjects($whereStatment, $cols, $args, $orderBy, $orderDirection);
    }

    /**
     * Prints the object once converted into HTML
     */
    public function toHtml()
    {
        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItems.php');

        //get the checklist item
        $checklistItem = ChecklistItems::withId($this->ChecklistItemId);

        ?>
        <div class="mdl-layout__tab-panel is-active" id="overview">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">
                    <div class="mdl-card__supporting-text">
                        <h4><?php echo $checklistItem->Title ?></h4>
                        Current Status -
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input id="Status" class="mdl-textfield__input mdl-js-button <?php echo (($this->Status == Status::COMPLETE) ? 'good' : 'bad') ?>" style="font-weight: bold; width: unset;" type="text" value="<?php echo $this->Status ?>" name="DataType" id="DataType"/>
                            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="Status">
                                <li class="mdl-menu__item datatype-menu-item" value="<?php echo Status::COMPLETE ?>"><span class="good" style="font-weight: bold"><?php echo Status::COMPLETE ?></span></li>
                                <li class="mdl-menu__item datatype-menu-item" value="<?php echo Status::INCOMPLETE ?>"><span class="bad" style="font-weight: bold"><?php echo Status::INCOMPLETE ?></span></li>
                            </ul>
                        </div>
                        <br><br>
                        <?php echo $checklistItem->Description ?><br><br>
                        <strong class="setting-title">Completed By</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" value="<?php echo $this->CompletedBy ?>" id="CompletedBy">
                        </div>
                        <strong class="setting-title">Completed On</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" value="<?php echo $this->CompletedDate ?>" id="CompletedDate">
                        </div>
                    </div>
                    <div class="card-buttons">
                        <button onclick="deleteRecord('<?php echo self::class ?>', <?php echo $this->Id ?>)" class="mdl-button mdl-js-button mdl-js-ripple-effect">
                            <span class="button-text">Delete</span>
                        </button>
                        <button onclick="saveRecord('<?php echo self::class ?>', <?php echo $this->Id ?>)" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                            <span class="button-text">Save</span>
                        </button>
                    </div>
                </div>
            </section>
        </div>

        <?php
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItems.php');
        return ChecklistItems::withId($this->ChecklistItemId)->Title;
    }

}

interface Status
{
    const COMPLETE = 'COMPLETE';
    const INCOMPLETE = 'INCOMPLETE';
}

?>