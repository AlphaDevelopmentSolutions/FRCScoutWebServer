<?php

class PitCards
{
    public $Id;
    public $TeamId;
    public $EventId;

    public $DriveStyle;
    public $RobotWeight;
    public $RobotLength;
    public $RobotWidth;
    public $RobotHeight;

    public $AutoExitHabitat;
    public $AutoHatch;
    public $AutoCargo;

    public $TeleopHatch;
    public $TeleopCargo;

    public $ReturnToHabitat;
    public $Notes;

    public $CompletedBy;

    private static $TABLE_NAME = 'pit_cards';

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return PitCards
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
     * @return PitCards
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
     * @return PitCards
     */
    protected function loadByProperties(Array $properties = array())
    {
        foreach($properties as $key => $value)
            $this->{$key} = $value;

    }

    /**
     * Loads a new instance by its database id
     * @param $id
     * @return PitCards
     */
    protected function loadById($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM ' . self::$TABLE_NAME . ' WHERE '.'id = '.$database->quote($id);
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
            $sql = 'INSERT INTO ' . PitCards::$TABLE_NAME . ' 
                                      (
                                      TeamId,
                                      EventId,
                                      
                                      DriveStyle,
                                      RobotWeight,
                                      RobotLength,
                                      RobotWidth,
                                      RobotHeight,
                                      
                                      AutoExitHabitat,
                                      AutoHatch,
                                      AutoCargo,
                                      
                                      TeleopHatch,
                                      TeleopCargo,
                                      
                                      ReturnToHabitat,
                                      
                                      Notes,
                                      
                                      CompletedBy
                                      )
                                      VALUES 
                                      (
                                      ' . ((empty($this->TeamId)) ? '0' : $database->quote($this->TeamId)) .',
                                      ' . ((empty($this->EventId)) ? 'NULL' : $database->quote($this->EventId)) .',
                                      
                                      ' . ((empty($this->DriveStyle)) ? 'NULL' : $database->quote($this->DriveStyle)) .',
                                      ' . ((empty($this->RobotWeight)) ? 'NULL' : $database->quote($this->RobotWeight)) .',
                                      ' . ((empty($this->RobotLength)) ? 'NULL' : $database->quote($this->RobotLength)) .',
                                      ' . ((empty($this->RobotWidth)) ? 'NULL' : $database->quote($this->RobotWidth)) .',
                                      ' . ((empty($this->RobotHeight)) ? 'NULL' : $database->quote($this->RobotHeight)) .',
                                      
                                      ' . ((empty($this->AutoExitHabitat)) ? 'NULL' : $database->quote($this->AutoExitHabitat)) .',
                                      ' . ((empty($this->AutoHatch)) ? 'NULL' : $database->quote($this->AutoHatch)) .',
                                      ' . ((empty($this->AutoCargo)) ? 'NULL' : $database->quote($this->AutoCargo)) .',
                                      
                                      ' . ((empty($this->TeleopHatch)) ? 'NULL' : $database->quote($this->TeleopHatch)) .',
                                      ' . ((empty($this->TeleopCargo)) ? 'NULL' : $database->quote($this->TeleopCargo)) .',
                                      
                                      ' . ((empty($this->ReturnToHabitat)) ? 'NULL' : $database->quote($this->ReturnToHabitat)) .',
                                      
                                      ' . ((empty($this->Notes)) ? 'NULL' : $database->quote($this->Notes)) .',
                                      
                                      ' . ((empty($this->CompletedBy)) ? 'NULL' : $database->quote($this->CompletedBy)) .'
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
            $sql = "UPDATE " . PitCards::$TABLE_NAME . " SET 
            TeamId = " . ((empty($this->TeamId)) ? "0" : $database->quote($this->TeamId)) .", 
            EventId = " . ((empty($this->EventId)) ? "NULL" : $database->quote($this->EventId)) .", 
            
            DriveStyle = " . ((empty($this->DriveStyle)) ? "NULL" : $database->quote($this->DriveStyle)) .", 
            RobotWeight = " . ((empty($this->RobotWeight)) ? "NULL" : $database->quote($this->RobotWeight)) .", 
            RobotLength = " . ((empty($this->RobotLength)) ? "NULL" : $database->quote($this->RobotLength)) .", 
            RobotWidth = " . ((empty($this->RobotWidth)) ? "NULL" : $database->quote($this->RobotWidth)) .", 
            RobotHeight = " . ((empty($this->RobotHeight)) ? "NULL" : $database->quote($this->RobotHeight)) .", 
            
            AutoExitHabitat = " . ((empty($this->AutoExitHabitat)) ? "NULL" : $database->quote($this->AutoExitHabitat)) .", 
            AutoHatch = " . ((empty($this->AutoHatch)) ? "NULL" : $database->quote($this->AutoHatch)) .", 
            AutoCargo = " . ((empty($this->AutoCargo)) ? "NULL" : $database->quote($this->AutoCargo)) .", 
            
            TeleopHatch = " . ((empty($this->TeleopHatch)) ? "NULL" : $database->quote($this->TeleopHatch)) .", 
            TeleopCargo = " . ((empty($this->TeleopCargo)) ? "NULL" : $database->quote($this->TeleopCargo)) .", 
            
            ReturnToHabitat = " . ((empty($this->ReturnToHabitat)) ? "NULL" : $database->quote($this->ReturnToHabitat)) .", 
            
            Notes = " . ((empty($this->Notes)) ? "NULL" : $database->quote($this->Notes)) .", 
            
            CompletedBy = " . ((empty($this->CompletedBy)) ? "NULL" : $database->quote($this->CompletedBy)) ."
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

    public static function getPitCardsForTeam($teamId, $eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . PitCards::$TABLE_NAME . " 
                    WHERE 
                      TeamId = " . $database->quote($teamId) .
                    'AND
                        EventId = ' . $database->quote($eventId) .
                    'ORDER BY Id DESC'
        );
        $database->close();

        $response = array();

        if($scoutCards && $scoutCards->num_rows > 0)
        {
            while ($row = $scoutCards->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public static function getNewestPitCard($teamId, $eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . PitCards::$TABLE_NAME . " 
                    WHERE 
                      TeamId = " . $database->quote($teamId) .
                    'AND
                        EventId = ' . $database->quote($eventId) .
                    'ORDER BY Id DESC LIMIT 1'
        );
        $database->close();

        $response = array();

        if($scoutCards && $scoutCards->num_rows > 0)
        {
            while ($row = $scoutCards->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public static function getPitCardsForEvent($eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . PitCards::$TABLE_NAME . " 
                    WHERE 
                        EventId = " . $database->quote($eventId)
        );
        $database->close();

        $response = array();

        if($scoutCards && $scoutCards->num_rows > 0)
        {
            while ($row = $scoutCards->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    /**
     * Gets and returns the team assigned to this pit card
     * @return Teams
     */
    public function getTeam()
    {
        return Teams::withId($this->TeamId);
    }

    /**
     * Returns the html for displaying a pit card
     * @return string html to display
     */
    public function toHtml()
    {
        require_once("Teams.php");

        //load the team from the database
        $team = $this->getTeam();

        $html = '
            <div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                        <h4 style="padding-left: 40px;">' . $team->toString() . '</h4>
                        <form method="post" action="' . $_SERVER['REQUEST_URI'] . '" id="pit-card-form">
                            <strong style="padding-left: 40px; padding-top: 10px;">Pre Game</strong>
                            <div class="mdl-card__supporting-text">
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->CompletedBy)) ? '&nbsp' : $this->CompletedBy) . '" name="completedBy">
                                    <label class="mdl-textfield__label" >Scouter</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->DriveStyle)) ? '&nbsp' : $this->DriveStyle) . '" name="driveStyle">
                                    <label class="mdl-textfield__label" >Drivetrain</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->RobotWeight)) ? '&nbsp' : $this->RobotWeight) . '" name="robotWeight">
                                    <label class="mdl-textfield__label" >Robot Weight</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->RobotLength)) ? '&nbsp' : $this->RobotLength) . '" name="robotLength">
                                    <label class="mdl-textfield__label" >Robot Length</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->RobotWidth)) ? '&nbsp' : $this->RobotWidth) . '" name="robotWidth">
                                    <label class="mdl-textfield__label" >Robot Width</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->RobotHeight)) ? '&nbsp' : $this->RobotHeight) . '" name="robotHeight">
                                    <label class="mdl-textfield__label" >Robot Height</label>
                                </div>
                            </div>
            
                            <strong style="padding-left: 40px; padding-top: 10px;">Autonomous</strong>
                            <div class="mdl-card__supporting-text">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AutoExitHabitat)) ? '&nbsp' : $this->AutoExitHabitat) . '" name="autonomousExitHabitat">
                                    <label class="mdl-textfield__label" >Exit Habitat</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AutoHatch)) ? '&nbsp' : $this->AutoHatch) . '" name="autonomousHatchPanelsSecured">
                                    <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AutoCargo)) ? '&nbsp' : $this->AutoCargo) . '" name="autonomousCargoStored">
                                    <label class="mdl-textfield__label" >Cargo Stored</label>
                                </div>
                            </div>
            
                            <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                            <div class="mdl-card__supporting-text">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->TeleopHatch)) ? '&nbsp' : $this->TeleopHatch) . '" name="teleopHatchPanelsSecured">
                                    <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->TeleopCargo)) ? '&nbsp' : $this->TeleopCargo) . '" name="teleopCargoStored">
                                    <label class="mdl-textfield__label" >Cargo Stored</label>
                                </div>
                            </div>
            
                            <strong style="padding-left: 40px; padding-top: 10px;">End Game</strong>
                            <div class="mdl-card__supporting-text">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->ReturnToHabitat)) ? '&nbsp' : $this->ReturnToHabitat) . '" name="returnedToHabitat">
                                    <label class="mdl-textfield__label" >Returned To Habitat</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <textarea ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input"  rows="3" name="notes" >' . ((empty($this->Notes)) ? '&nbsp' : $this->Notes) . '</textarea>
                                    <label class="mdl-textfield__label" >Notes</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>';

        return $html;
    }


}

?>