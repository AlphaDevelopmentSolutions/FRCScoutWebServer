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
     * @param LocalDatabase $database
     * @param Matches | null $match if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return ChecklistItemResults[]
     */
    public static function getObjects($database, $match = null, $orderBy = 'Id', $orderDirection = 'ASC')
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

        return parent::getObjects($database, $whereStatment, $cols, $args, $orderBy, $orderDirection);
    }

    /**
     * Overrides parent save function to overwrite existing records in case of conflicts
     * @param LocalDatabase $database
     * @param CoreDatabase $coreDatabase
     * @param boolean $bypassOverwriteCheck bypasses overwrite check when saving
     * @return bool
     */
    public function save($database, $coreDatabase, $bypassOverwriteCheck = false)
    {
        require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
        require_once(ROOT_DIR . '/classes/tables/core/Events.php');
        require_once(ROOT_DIR . '/classes/tables/core/Matches.php');
        require_once(ROOT_DIR . '/classes/tables/core/Years.php');
        require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfoKeys.php');

        if(!$bypassOverwriteCheck)
        {
            $objs = self::getObjects($database, Matches::withId($coreDatabase, $this->MatchId));

            foreach ($objs as $obj)
            {
                if ($this->ChecklistItemId == $obj->ChecklistItemId)
                    $this->Id = $obj->Id;
            }
        }

        return parent::save($database);
    }

    /**
     * Prints the object once converted into HTML
     * @param LocalDatabase $database
     */
    public function toHtml($database = null)
    {
        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItems.php');

        //get the checklist item
        $checklistItem = ChecklistItems::withId($database, $this->ChecklistItemId);

        ?>
        <div class="mdl-layout__tab-panel is-active" id="overview">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col" checklist-item-id="<?php echo $this->ChecklistItemId ?>" match-id="<?php echo $this->MatchId ?>">
                    <div class="mdl-card__supporting-text">
                        <h4><?php echo $checklistItem->Title ?></h4>
                        Current Status -
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input mdl-js-button Status <?php echo (($this->Status == Status::COMPLETE) ? 'good' : 'bad') ?>" style="font-weight: bold; min-width: 110px;" id="Status<?php echo $this->ChecklistItemId ?>" type="text" value="<?php echo $this->Status ?>"/>
                            <?php
                            if(getUser()->IsAdmin == 1)
                            {
                            ?>
                            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="Status<?php echo $this->ChecklistItemId ?>">
                                <li class="mdl-menu__item datatype-menu-item" value="<?php echo Status::COMPLETE ?>"><span class="good" style="font-weight: bold"><?php echo Status::COMPLETE ?></span></li>
                                <li class="mdl-menu__item datatype-menu-item" value="<?php echo Status::INCOMPLETE ?>"><span class="bad" style="font-weight: bold"><?php echo Status::INCOMPLETE ?></span></li>
                            </ul>
                            <?php
                            }
                            ?>
                        </div>
                        <br><br>
                        <?php echo $checklistItem->Description ?><br><br>
                        <strong class="setting-title">Completed By</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input <?php echo getUser()->IsAdmin == 1 ? '' : 'disabled' ?> class="mdl-textfield__input CompletedBy" value="<?php echo $this->CompletedBy ?>">
                        </div>
                        <strong class="setting-title">Completed On</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input <?php echo getUser()->IsAdmin == 1 ? '' : 'disabled' ?> class="mdl-textfield__input CompletedDate" value="<?php echo substr($this->CompletedDate, 0, strpos($this->CompletedDate, ' ')) ?>">
                        </div>
                        <strong class="setting-title">Completed At</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input <?php echo getUser()->IsAdmin == 1 ? '' : 'disabled' ?> class="mdl-textfield__input CompletedTime" value="<?php echo substr($this->CompletedDate, strpos($this->CompletedDate, ' ') + 1) ?>">
                        </div>
                    </div>
                    <?php
                    if(getUser()->IsAdmin == 1)
                    {
                    ?>
                    <div class="card-buttons">
                        <button onclick="deleteRecord('<?php echo self::class ?>', <?php echo empty($this->Id) ? 'undefined' : $this->Id ?>)" class="mdl-button mdl-js-button mdl-js-ripple-effect">
                            <span class="button-text">Delete</span>
                        </button>
                        <button onclick="saveRecord('<?php echo self::class ?>', <?php echo empty($this->Id) ? 'undefined' : $this->Id ?>, $(this).parent().parent())" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                            <span class="button-text">Save</span>
                        </button>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </section>
        </div>

        <?php
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @param LocalDatabase $database
     * @return string
     */
    public function toString($database = null)
    {
        require_once(ROOT_DIR . '/classes/tables/local/ChecklistItems.php');
        return ChecklistItems::withId($database, $this->ChecklistItemId)->Title;
    }

}

interface Status
{
    const COMPLETE = 'COMPLETE';
    const INCOMPLETE = 'INCOMPLETE';
}

?>