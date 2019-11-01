<?php

class RobotInfoKeys extends LocalTable
{
    public $Id;
    public $YearId;
    public $KeyState;
    public $KeyName;
    public $SortOrder;

    public static $TABLE_NAME = 'robot_info_keys';

    /**
     * Retrieves objects from the database
     * @param LocalDatabase $database
     * @param Years | null $year if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return RobotInfoKeys[]
     */
    public static function getObjects($database, $year = null, $orderBy = 'SortOrder', $orderDirection = 'ASC')
    {
        $whereStatement = "";
        $cols = array();
        $args = array();

        //if year specified, filter by year
        if (!empty($year)) {
            $whereStatement .= ((empty($whereStatement)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        return parent::getObjects($database, $whereStatement, $cols, $args, $orderBy, $orderDirection);
    }

    /**
     * Gets and returns all keys from the database
     * @param LocalDatabase $database
     * @param Years | null $year if specified, filters keys by year
     * @param string | null $keyState if specified, filters keys by state
     * @return RobotInfoKeys[]
     */
    public static function getKeys($database, $year = null, $keyState = null)
    {
        $yearId = ((!empty($year)) ? $year->Id : ((!empty($event)) ? $event->YearId : date('Y')));

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'YearId';
        $args[] = $yearId;

        if (!empty($keyState)) {
            $sql .= " AND ! = ? ";
            $cols[] = 'KeyState';
            $args[] = $keyState;
        }

        $sql .= " ORDER BY ! ASC";
        $cols[] = 'SortOrder';

        $rows = self::queryRecords($database, $sql, $cols, $args);

        foreach ($rows as $row)
            $response[] = RobotInfoKeys::withProperties($row);

        return $response;
    }

    /**
     * Override for the Table class delete function
     * Ensures all records associated with this key are deleted before deletion
     * @param LocalDatabase $database
     * @return bool
     */
    public function delete($database)
    {
        if (!empty($this->Id)) {
            require_once(ROOT_DIR . '/classes/tables/local/RobotInfo.php');

            //create the sql statement
            $sql = "DELETE FROM ! WHERE ! = ? AND ! = ?";
            $cols[] = RobotInfo::$TABLE_NAME;

            //Where
            $cols[] = 'YearId';
            $args[] = $this->YearId;

            $cols[] = 'PropertyKeyId';
            $args[] = $this->Id;

            self::deleteRecords($database, $sql, $cols, $args);
        }

        return parent::delete($database);
    }

    public function toString()
    {
        return $this->KeyName;
    }

    public function toHtml()
    {
        return '';
    }

    /**
     * Displays the object once converted into HTML
     * @param LocalDatabase $database
     * @param CoreDatabase $coreDatabase
     * @param $event Events
     * @param $team Teams
     */
    public static function toCard($database, $coreDatabase, $event, $team)
    {
        require_once(ROOT_DIR . "/classes/tables/core/Years.php");

        $robotInfoArray = array();
        $robotInfoKeyStates = array();
        $year = Years::withId($coreDatabase, $event->YearId);
        $robotInfoKeys = self::getObjects($database, $year);
        $robotInfos = RobotInfo::getObjects($database, null, null, $event, $team, $robotInfoKeys);

        foreach ($robotInfos as $robotInfo) {
            $robotInfoKey = null;

            for ($i = 0; $i < sizeof($robotInfoKeys) && empty($robotInfoKey); $i++) {
                if ($robotInfo->PropertyKeyId == $robotInfoKeys[$i]->Id)
                    $robotInfoKey = $robotInfoKeys[$i];
            }

            $robotInfoArray[$robotInfoKey->KeyState][$robotInfoKey->KeyName] = $robotInfo;
        }

        //get the keys for the specified year and store the states for sections
        foreach ($robotInfoKeys as $robotInfoKey)
            $robotInfoKeyStates[] = $robotInfoKey->KeyState;
        $robotInfoKeyStates = array_unique($robotInfoKeyStates);

        ?>
        <div class="mdl-layout__tab-panel is-active">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">
                    <?php

                    foreach ($robotInfoKeyStates as $robotInfoKeyState) {
                        ?>
                        <div class="mdl-card__supporting-text" style="margin: 0 40px !important;">
                            <h5><?php echo $robotInfoKeyState ?></h5>
                            <hr>
                            <?php
                            foreach (self::getKeys($database, $year, $robotInfoKeyState) as $robotInfoKey) {
                                ?>
                                <strong class="setting-title"><?php echo $robotInfoKey->KeyName ?></strong>
                                <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label"
                                     data-upgraded=",MaterialTextfield">
                                    <input class="mdl-textfield__input robot-info-field"
                                           info-id="<?php echo((!empty($robotInfoArray[$robotInfoKey->KeyState][$robotInfoKey->KeyName]->Id)) ? $robotInfoArray[$robotInfoKey->KeyState][$robotInfoKey->KeyName]->Id : -1) ?>"
                                           year-id="<?php echo $year->Id ?>"
                                           event-id="<?php echo $event->BlueAllianceId ?>"
                                           team-id="<?php echo $team->Id ?>"
                                           info-key-id="<?php echo $robotInfoKey->Id ?>"
                                           value="<?php echo $robotInfoArray[$robotInfoKey->KeyState][$robotInfoKey->KeyName]->PropertyValue ?>">
                                </div>
                                <?php
                            }

                            ?>
                        </div>
                        <?php
                    }
                    if(getUser()->IsAdmin == 1) {
                        ?>
                        <div class="card-buttons">
                            <button id="delete"
                                    onclick="deleteRecord('<?php echo RobotInfo::class ?>', -1, {teamId: <?php echo $team->Id ?>, eventId: '<?php echo $event->BlueAllianceId ?>'})"
                                    class="mdl-button mdl-js-button mdl-js-ripple-effect">
                                <span class="button-text">Delete</span>
                            </button>
                            <button id="save" onclick="saveRecordOverride('<?php echo RobotInfo::class ?>', -1)"
                                    class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                                <span class="button-text">Save</span>
                            </button>
                            <span hidden id="loading">
                                Saving Robot Info...
                                <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                            </span>
                        </div>
                        <?php
                    }
                        ?>
                </div>
            </section>
        </div>
        <?php
    }
}