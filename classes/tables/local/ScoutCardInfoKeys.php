<?php

class ScoutCardInfoKeys extends LocalTable
{
    public $Id;
    public $YearId;

    public $KeyState;
    public $KeyName;

    public $SortOrder;

    public $MinValue;
    public $MaxValue;

    public $NullZeros;
    public $IncludeInStats;

    public $DataType;

    public static $TABLE_NAME = 'scout_card_info_keys';

    /**
     * Retrieves objects from the database
     * @param Years | null $year if specified, filters by id
     * @param string $orderBy order field to sort items by
     * @param string $orderDirection direction to sort items by
     * @return ScoutCardInfoKeys[]
     */
    public static function getObjects($year = null, $orderBy = 'SortOrder', $orderDirection = 'ASC')
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
     * Gets and returns all keys from the database
     * @param Years | null $year if specified, filters keys by year
     * @param Events | null $event if specified, filters keys by event
     * @param string | null $keyState if specified, filters keys by state
     * @return ScoutCardInfoKeys[]
     */
    public static function getKeys($year = null, $event = null, $keyState = null)
    {
        $yearId = ((!empty($year)) ? $year->Id : ((!empty($event)) ? $event->YearId : date('Y')));

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'YearId';
        $args[] = $yearId;

        if(!empty($keyState))
        {
            $sql .= " AND ! = ? ";
            $cols[] = 'KeyState';
            $args[] = $keyState;
        }

        $sql .= " ORDER BY ! ASC";
        $cols[] = 'SortOrder';

        $rows = self::queryRecords($sql, $cols, $args);

        foreach($rows as $row)
            $response[] = self::withProperties($row);

        return $response;
    }

    /**
     * @return static
     */
    public static function withStateAndName($keyState, $keyName)
    {
        $instance = new self();
        $instance->loadByStateAndKey($keyState, $keyName);
        return $instance;
    }

    /**
     * Loads a new instance by its database id
     * @return boolean
     */
    protected function loadByStateAndKey($keyState, $keyName)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? AND ! = ?";
        $cols[] = self::$TABLE_NAME;

        $cols[] = 'KeyState';
        $args[] = $keyState;
        $cols[] = 'KeyName';
        $args[] = $keyName;

        $rows = self::queryRecords($sql, $cols, $args);

        foreach ($rows as $row)
        {
            foreach($row as $key => $value)
            {
                if(property_exists($this, $key))
                    $this->$key = $value;

            }

            return true;
        }

        return false;
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
            require_once(ROOT_DIR . '/classes/tables/local/ScoutCardInfo.php');

            //create the sql statement
            $sql = "DELETE FROM ! WHERE ! = ? AND ! = ?";
            $cols[] = ScoutCardInfo::$TABLE_NAME;

            //Where
            $cols[] = 'YearId';
            $args[] = $this->YearId;

            $cols[] = 'PropertyKeyId';
            $args[] = $this->Id;

            self::deleteRecords($sql, $cols, $args);
        }

        return parent::delete();
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
     * @param $event Events
     * @param $match Matches
     * @param $team Teams
     */
    public static function toCard($event, $match, $team)
    {
        require_once(ROOT_DIR . "/classes/tables/core/Years.php");

        $scoutCardInfoArray = array();
        $scoutCardInfoKeyStates = array();
        $year = Years::withId($event->YearId);
        $scoutCardInfoKeys = self::getObjects($year);
        $scoutCardInfos = ScoutCardInfo::getObjects(null, null, $event, $match, $team, $scoutCardInfoKeys);

        foreach ($scoutCardInfos as $scoutCardInfo) {
            $scoutCardInfoKey = null;

            for ($i = 0; $i < sizeof($scoutCardInfoKeys) && empty($scoutCardInfoKey); $i++) {
                if ($scoutCardInfo->PropertyKeyId == $scoutCardInfoKeys[$i]->Id)
                    $scoutCardInfoKey = $scoutCardInfoKeys[$i];
            }

            $scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName] = $scoutCardInfo;
        }

        //get the keys for the specified year and store the states for sections
        foreach ($scoutCardInfoKeys as $scoutCardInfoKey)
            $scoutCardInfoKeyStates[] = $scoutCardInfoKey->KeyState;
        $scoutCardInfoKeyStates = array_unique($scoutCardInfoKeyStates);

        ?>
        <div class="mdl-layout__tab-panel is-active">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">
                    <h4 style="padding-left: 40px;"><a class="link" href="<?php echo TEAMS_URL . 'match-list?teamId=' . $team->Id . '&eventId=' . $event->BlueAllianceId ?>"><?php echo $team->toString() ?></a></h4>
                    <?php

                    foreach ($scoutCardInfoKeyStates as $scoutCardInfoKeyState) {
                        ?>
                        <div class="mdl-card__supporting-text" style="margin: 0 40px !important;">
                            <h5><?php echo $scoutCardInfoKeyState ?></h5>
                            <hr>
                            <?php
                            foreach (self::getKeys($year, null, $scoutCardInfoKeyState) as $scoutCardInfoKey) {
                                ?>
                                <strong class="setting-title"><?php echo $scoutCardInfoKey->KeyName ?></strong>
                                <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label"
                                     data-upgraded=",MaterialTextfield">
                                    <?php
                                        switch($scoutCardInfoKey->DataType)
                                        {
                                            case DataTypes::BOOL:
                                                ?>
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                                                    <input type="checkbox" class="mdl-switch__input scout-card-info-field"
                                                           info-id="<?php echo((!empty($scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName]->Id)) ? $scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName]->Id : -1) ?>"
                                                           year-id="<?php echo $year->Id ?>"
                                                           event-id="<?php echo $event->BlueAllianceId ?>"
                                                           match-id="<?php echo $match->Key ?>"
                                                           team-id="<?php echo $team->Id ?>"
                                                           info-key-id="<?php echo $scoutCardInfoKey->Id ?>"
                                                           datatype="<?php echo $scoutCardInfoKey->DataType ?>"
                                                           <?php if($scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName]->PropertyValue == 1) echo "checked" ?>>
                                                </label>
                                                <?php
                                                break;

                                            case DataTypes::INT:
                                                ?>
                                                <input type="number" class="mdl-textfield__input scout-card-info-field"
                                                       info-id="<?php echo((!empty($scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName]->Id)) ? $scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName]->Id : -1) ?>" year-id="<?php echo $year->Id ?>"
                                                       event-id="<?php echo $event->BlueAllianceId ?>"
                                                       match-id="<?php echo $match->Key ?>"
                                                       team-id="<?php echo $team->Id ?>"
                                                       info-key-id="<?php echo $scoutCardInfoKey->Id ?>"
                                                       datatype="<?php echo $scoutCardInfoKey->DataType ?>"
                                                       value="<?php echo $scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName]->PropertyValue ?>">
                                                <?php
                                                break;

                                            case DataTypes::TEXT:
                                                ?>
                                                <input type="text" class="mdl-textfield__input scout-card-info-field"
                                                       info-id="<?php echo((!empty($scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName]->Id)) ? $scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName]->Id : -1) ?>"
                                                       year-id="<?php echo $year->Id ?>"
                                                       event-id="<?php echo $event->BlueAllianceId ?>"
                                                       match-id="<?php echo $match->Key ?>"
                                                       team-id="<?php echo $team->Id ?>"
                                                       info-key-id="<?php echo $scoutCardInfoKey->Id ?>"
                                                       datatype="<?php echo $scoutCardInfoKey->DataType ?>"
                                                       value="<?php echo $scoutCardInfoArray[$scoutCardInfoKey->KeyState][$scoutCardInfoKey->KeyName]->PropertyValue ?>">
                                                <?php
                                                break;
                                        }
                                    ?>
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
                                    onclick="deleteRecord('<?php echo ScoutCardInfo::class ?>', -1, {teamId: <?php echo $team->Id ?>, eventId: '<?php echo $event->BlueAllianceId ?>', matchId: '<?php echo $match->Key ?>'})"
                                    class="mdl-button mdl-js-button mdl-js-ripple-effect">
                                <span class="button-text">Delete</span>
                            </button>
                            <button id="save"
                                    onclick="saveRecordOverride('<?php echo ScoutCardInfo::class ?>', -1, $(this).parent().parent())"
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

interface DataTypes
{
    const INT = 'INT';
    const BOOL = 'BOOL';
    const TEXT = 'TEXT';

    const TEXT_PLAIN_TEXT = "Text";
    const INT_PLAIN_TEXT = "Number";
    const BOOL_PLAIN_TEXT = "True & False";

    const DATA_TYPES = [self::INT, self::BOOL, self::TEXT];

    const DATATYPE_TO_PLAIN_TEXT_ARRAY =
        [
            self::TEXT => self::TEXT_PLAIN_TEXT,
            self::INT => self::INT_PLAIN_TEXT,
            self::BOOL => self::BOOL_PLAIN_TEXT
        ];

    const PLAIN_TEXT_TO_DATATYPE_ARRAY =
        [
            self::TEXT_PLAIN_TEXT => self::TEXT,
            self::INT_PLAIN_TEXT => self::INT,
            self::BOOL_PLAIN_TEXT => self::BOOL
        ];
}