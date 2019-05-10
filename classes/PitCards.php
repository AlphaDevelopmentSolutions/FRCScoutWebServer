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

    public static $TABLE_NAME = 'pit_cards';

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {
        require_once(ROOT_DIR . '/classes/Teams.php');

        //load the team from the database
        $team = Teams::withId($this->TeamId);

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

    /**
     * Returns the html for displaying a pit card as a card
     * @return string
     */
    public function toCard()
    {
        $html =
            '<div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                        <div class="mdl-card__supporting-text">
                            <h4>' . $this->toString() . '</h4>
                            Completed By: ' . $this->CompletedBy .
                        '</div>
                        <div class="mdl-card__actions">
                            <a href="/pit-card.php?pitCardId=' . $this->Id  .'" class="mdl-button">View</a>
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
        return 'Pit Card - ' . $this->Id;
    }
}

?>