<?php

class ScoutCards extends Table
{
    public $Id;
    public $MatchId;
    public $TeamId;
    public $EventId;
    public $AllianceColor;
    public $CompletedBy;

    public $PreGameStartingPosition;
    public $PreGameStartingLevel;
    public $PreGameStartingPiece;

    public $AutonomousExitHabitat;
    public $AutonomousHatchPanelsPickedUp;
    public $AutonomousHatchPanelsSecuredAttempts;
    public $AutonomousHatchPanelsSecured;
    public $AutonomousCargoPickedUp;
    public $AutonomousCargoStoredAttempts;
    public $AutonomousCargoStored;

    public $TeleopHatchPanelsPickedUp;
    public $TeleopHatchPanelsSecuredAttempts;
    public $TeleopHatchPanelsSecured;
    public $TeleopCargoPickedUp;
    public $TeleopCargoStoredAttempts;
    public $TeleopCargoStored;

    public $EndGameReturnedToHabitat;
    public $EndGameReturnedToHabitatAttempts;

    public $BlueAllianceFinalScore;
    public $RedAllianceFinalScore;
    public $DefenseRating;
    public $OffenseRating;
    public $DriveRating;
    public $Notes;
    public $CompletedDate;

    protected static $TABLE_NAME = 'scout_cards';

    /**
     * Loads a new instance by specified properties
     * @param int $teamId
     * @param string $matchKey
     * @return ScoutCards
     */
    static function forMatch($teamId, $matchKey)
    {
        //crate a new instance
        $instance = new self();

        //gather results from database, limit to the newest scout card only
        $database = new Database();
        $sql = "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME ." 
                    WHERE 
                      TeamId = " . $database->quote($teamId) .
                    ' AND
                      MatchId = ' . $database->quote($matchKey) .
                    ' ORDER BY Id DESC LIMIT 1';

        $scoutCards = $database->query($sql);
        $database->close();

        //assign results
        if($scoutCards && $scoutCards->num_rows > 0)
            $instance->loadByProperties($scoutCards->fetch_assoc());

        return $instance;

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

    public static function getScoutCardsForTeam($teamId, $eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME ." 
                    WHERE 
                      TeamId = " . $database->quote($teamId) .
                    'AND
                        EventId = ' . $database->quote($eventId) .
                    'ORDER BY MatchId DESC'
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

    public static function getScoutCardsForEvent($eventId)
    {
        $database = new Database();
        $scoutCards = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME ."  
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
     * Gets and returns the team assigned to this scout card
     * @return Teams
     */
    public function getTeam()
    {
        return Teams::withId($this->TeamId);
    }

    /**
     * Returns the html for displaying a scout card
     * @return string html to display
     */
    public function toHtml()
    {
        require_once("Teams.php");

        //load the stars to be shown
        $defenseStars = '&nbsp';
        $offenseStars = '&nbsp';
        $driveStars = '&nbsp';

        for($i = 0; $i < $this->DefenseRating; $i++)
            $defenseStars .= "&#9733;";

        for($i = 0; $i < $this->OffenseRating; $i++)
            $offenseStars .= "&#9733;";

        for($i = 0; $i < $this->DriveRating; $i++)
            $driveStars .= "&#9733;";

        //load the team from the database
        $team = $this->getTeam();

        $html = '
            <div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                    <h4 style="padding-left: 40px;">' . $team->toString() . '</h4>
                        <form method="post" action="' . $_SERVER['REQUEST_URI'] . '" id="scout-card-form">
                            <strong style="padding-left: 40px;">Pre Game</strong>
                            <div class="mdl-card__supporting-text">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->CompletedBy)) ? '&nbsp' : $this->CompletedBy) . '" name="completedBy">
                                    <label class="mdl-textfield__label" >Scouter</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AllianceColor)) ? '&nbsp' : $this->AllianceColor) . '" name="allianceColor">
                                    <label class="mdl-textfield__label" >AllianceColor</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->PreGameStartingLevel)) ? 'nbsp' : 'Level ' . $this->PreGameStartingLevel) . '" name="redAllianceScore">
                                    <label class="mdl-textfield__label" >Starting Level</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->PreGameStartingPosition)) ? '&nbsp' : $this->PreGameStartingPosition) . '" name="redAllianceScore">
                                    <label class="mdl-textfield__label" >Starting Position</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->PreGameStartingPiece)) ? '&nbsp' : $this->PreGameStartingPiece) . '" name="redAllianceScore">
                                    <label class="mdl-textfield__label" >Starting Piece</label>
                                </div>
                            </div>
            
                            <strong style="padding-left: 40px; padding-top: 10px;">Autonomous</strong>
                            <div class="mdl-card__supporting-text">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . (($this->AutonomousExitHabitat == 1) ? 'Yes' : 'No'). '" name="autonomousExitHabitat">
                                    <label class="mdl-textfield__label" >Exit Habitat</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AutonomousHatchPanelsPickedUp)) ? 0 : $this->AutonomousHatchPanelsPickedUp) . '" name="autonomousHatchPanelsPickedUp">
                                    <label class="mdl-textfield__label" >Hatch Panels Picked Up</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AutonomousHatchPanelsSecuredAttempts)) ? 0 : $this->AutonomousHatchPanelsSecuredAttempts) . '" name="autonomousHatchPanelsSecuredAttempts">
                                    <label class="mdl-textfield__label" >Hatch Panels Dropped</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AutonomousHatchPanelsSecured)) ? 0 : $this->AutonomousHatchPanelsSecured) . '" name="autonomousHatchPanelsSecured">
                                    <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AutonomousCargoPickedUp)) ? 0 : $this->AutonomousCargoPickedUp) . '" name="autonomousCargoPickedUp">
                                    <label class="mdl-textfield__label" >Cargo Picked Up</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AutonomousCargoStoredAttempts)) ? 0 : $this->AutonomousCargoStoredAttempts) . '" name="autonomousCargoStoredAttempts">
                                    <label class="mdl-textfield__label" >Cargo Dropped</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->AutonomousCargoStored)) ?  0 : $this->AutonomousCargoStored) . '" name="autonomousCargoStored">
                                    <label class="mdl-textfield__label" >Cargo Stored</label>
                                </div>
                                
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . round(($this->AutonomousHatchPanelsSecured / ($this->AutonomousHatchPanelsPickedUp + 1)) * 100, 2) . '%">
                                    <label class="mdl-textfield__label" >Hatch Secure %</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . round(($this->AutonomousCargoStored / ($this->AutonomousCargoPickedUp + 1)) * 100, 2) . '%">
                                    <label class="mdl-textfield__label" >Cargo Store %</label>
                                </div>
                            </div>
            
                            <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                            <div class="mdl-card__supporting-text">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->TeleopHatchPanelsPickedUp)) ? 0 : $this->TeleopHatchPanelsPickedUp) . '" name="TeleopHatchPanelsPickedUp">
                                    <label class="mdl-textfield__label" >Hatch Panels Picked Up</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->TeleopHatchPanelsSecuredAttempts)) ? 0 : $this->TeleopHatchPanelsSecuredAttempts) . '" name="TeleopHatchPanelsSecuredAttempts">
                                    <label class="mdl-textfield__label" >Hatch Panels Dropped</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->TeleopHatchPanelsSecured)) ? 0 : $this->TeleopHatchPanelsSecured) . '" name="TeleopHatchPanelsSecured">
                                    <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->TeleopCargoPickedUp)) ? 0 : $this->TeleopCargoPickedUp) . '" name="teleopCargoPickedUp">
                                    <label class="mdl-textfield__label" >Cargo Picked Up</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->TeleopCargoStoredAttempts)) ? 0 : $this->TeleopCargoStoredAttempts) . '" name="teleopCargoStoreddAttempts">
                                    <label class="mdl-textfield__label" >Cargo Dropped</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->TeleopCargoStored)) ? 0 : $this->TeleopCargoStored) . '" name="teleopCargoStored">
                                    <label class="mdl-textfield__label" >Cargo Stored</label>
                                </div>
                                
                                 <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . round(($this->TeleopHatchPanelsSecured / (($this->TeleopHatchPanelsPickedUp == 0) ? 1 : $this->TeleopHatchPanelsPickedUp)) * 100, 2) . '%">
                                    <label class="mdl-textfield__label" >Hatch Secure %</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . round(($this->TeleopCargoStored / (($this->TeleopCargoPickedUp == 0) ? 1 : $this->TeleopCargoPickedUp)) * 100, 2) . '%">
                                    <label class="mdl-textfield__label" >Cargo Store %</label>
                                </div>
            
                            </div>
            
                            <strong style="padding-left: 40px; padding-top: 10px;">End Game</strong>
                            <div class="mdl-card__supporting-text">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->EndGameReturnedToHabitat)) ? 'No' : 'Level ' . $this->EndGameReturnedToHabitat) . '" name="returnedToHabitat">
                                    <label class="mdl-textfield__label" >Returned To Habitat</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->EndGameReturnedToHabitatAttempts)) ? 'No' : 'Level ' . $this->EndGameReturnedToHabitatAttempts) . '" name="returnedToHabitatAttempts">
                                    <label class="mdl-textfield__label" >Returned To Habitat Failed  Attempt</label>
                                </div>
                            </div>
            
                            <strong style="padding-left: 40px; padding-top: 10px;">Post Game</strong>
                            <div class="mdl-card__supporting-text">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->BlueAllianceFinalScore)) ? '&nbsp' : $this->BlueAllianceFinalScore) . '" name="blueAllianceScore">
                                    <label class="mdl-textfield__label" >Blue Alliance Score</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . ((empty($this->RedAllianceFinalScore)) ? '&nbsp' : $this->RedAllianceFinalScore) . '" name="redAllianceScore">
                                    <label class="mdl-textfield__label" >Red Alliance Score</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . $defenseStars . '" name="defenseRating">
                                    <label class="mdl-textfield__label" >Defense Rating</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . $offenseStars . '" name="offenseRating">
                                    <label class="mdl-textfield__label" >Offense Rating</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" value="' . $driveStars . '" name="driveRating">
                                    <label class="mdl-textfield__label" >Drive Rating</label>
                                </div>
            
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <textarea ' . ((loggedIn()) ? "" : "disabled") . ' class="mdl-textfield__input" type="text" rows="3" name="notes" >' . ((empty($this->Notes)) ? '&nbsp' : $this->Notes) . '</textarea>
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