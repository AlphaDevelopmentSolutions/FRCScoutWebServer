<?php

class PitCards extends Table
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

    protected static $TABLE_NAME = 'pit_cards';

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

    public function toString()
    {
        // TODO: Implement toString() method.
    }
}

?>