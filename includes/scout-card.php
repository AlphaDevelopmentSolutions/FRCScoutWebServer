<div class="mdl-layout__tab-panel is-active" id="overview">
    <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
        <div class="mdl-card mdl-cell mdl-cell--12-col">
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" style="padding-top: 30px;" id="scout-card-form">
                <strong style="padding-left: 40px;">Pre Game</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeamId ?>" name="teamId">
                        <label class="mdl-textfield__label" >Team Id</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->CompletedBy ?>" name="completedBy">
                        <label class="mdl-textfield__label" >Scouter</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->MatchId ?>" name="matchId">
                        <label class="mdl-textfield__label" >Match Number</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AllianceColor ?>" name="allianceColor">
                        <label class="mdl-textfield__label" >AllianceColor</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo ((empty($scoutCard->PreGameStartingLevel)) ? '' : 'Level ' . $scoutCard->PreGameStartingLevel) ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Starting Level</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->PreGameStartingPosition ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Starting Position</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->PreGameStartingPiece ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Starting Piece</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Autonomous</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo (($scoutCard->AutonomousExitHabitat == 1) ? 'Yes' : 'No') ?>" name="autonomousExitHabitat">
                        <label class="mdl-textfield__label" >Exit Habitat</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousHatchPanelsPickedUp ?>" name="autonomousHatchPanelsPickedUp">
                        <label class="mdl-textfield__label" >Hatch Panels Picked Up</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousHatchPanelsSecuredAttempts ?>" name="autonomousHatchPanelsSecuredAttempts">
                        <label class="mdl-textfield__label" >Hatch Panels Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousHatchPanelsSecured ?>" name="autonomousHatchPanelsSecured">
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousHatchPanelsPickedUp / $scoutCard->AutonomousHatchPanelsSecured ?>">
                        <label class="mdl-textfield__label" >Hatch Pickup / Secure %</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousCargoPickedUp ?>" name="autonomousCargoPickedUp">
                        <label class="mdl-textfield__label" >Cargo Picked Up</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousCargoStoredAttempts ?>" name="autonomousCargoStoredAttempts">
                        <label class="mdl-textfield__label" >Cargo Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousCargoStored ?>" name="autonomousCargoStored">
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousCargoPickedUp / $scoutCard->AutonomousCargoStored ?>">
                        <label class="mdl-textfield__label" >Cargo Pickup / Stored %</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopHatchPanelsPickedUp ?>" name="TeleopHatchPanelsPickedUp">
                        <label class="mdl-textfield__label" >Hatch Panels Picked Up</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopHatchPanelsSecuredAttempts ?>" name="TeleopHatchPanelsSecuredAttempts">
                        <label class="mdl-textfield__label" >Hatch Panels Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopHatchPanelsSecured ?>" name="TeleopHatchPanelsSecured">
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopHatchPanelsPickedUp / $scoutCard->TeleopHatchPanelsSecured ?>">
                        <label class="mdl-textfield__label" >Hatch Pickup / Secure %</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopCargoPickedUp ?>" name="teleopCargoPickedUp">
                        <label class="mdl-textfield__label" >Cargo Picked Up</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopCargoStoredAttempts ?>" name="teleopCargoStoreddAttempts">
                        <label class="mdl-textfield__label" >Cargo Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopCargoStored ?>" name="teleopCargoStored">
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopCargoPickedUp / $scoutCard->TeleopCargoStored ?>">
                        <label class="mdl-textfield__label" >Cargo Pickup / Stored %</label>
                    </div>

                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">End Game</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->EndGameReturnedToHabitat) ? 'No' : 'Level ' . $scoutCard->EndGameReturnedToHabitat ?>" name="returnedToHabitat">
                        <label class="mdl-textfield__label" >Returned To Habitat</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->EndGameReturnedToHabitatAttempts) ? 'No' : 'Level ' . $scoutCard->EndGameReturnedToHabitatAttempts ?>" name="returnedToHabitatAttempts">
                        <label class="mdl-textfield__label" >Returned To Habitat Failed  Attempt</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Post Game</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->BlueAllianceFinalScore ?>" name="blueAllianceScore">
                        <label class="mdl-textfield__label" >Blue Alliance Score</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->RedAllianceFinalScore ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Red Alliance Score</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php for($i = 0; $i < $scoutCard->DefenseRating; $i++) echo "&#9733;"?>" name="defenseRating">
                        <label class="mdl-textfield__label" >Defense Rating</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php for($i = 0; $i < $scoutCard->OffenseRating; $i++) echo "&#9733;"?>" name="offenseRating">
                        <label class="mdl-textfield__label" >Offense Rating</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php for($i = 0; $i < $scoutCard->DriveRating; $i++) echo "&#9733;"?>" name="driveRating">
                        <label class="mdl-textfield__label" >Drive Rating</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <textarea class="mdl-textfield__input" type="text" rows="3" name="notes" ><?php echo $scoutCard->Notes ?></textarea>
                        <label class="mdl-textfield__label" >Notes</label>
                    </div>


                </div>
                <?php

                if(loggedIn())
                {
                    //temp disabled due to new db design
//                    echo
//                    '<div class="mdl-card__supporting-text">
//                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
//                                  <div class="mdl-card__supporting-text">
//                                      <div class="mdl-card__supporting-text" style="margin-bottom: 30px;">
//                                          <button name="save" type="submit" class="mdl-button mdl-js-button mdl-button--raised">
//                                            Save
//                                          </button>
//                                      </div>
//                                  </div>
//                              </div>';
//
//                    if(!empty($scoutCard->Id))
//                        echo
//                        '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
//                                  <div class="mdl-card__supporting-text">
//                                      <div class="mdl-card__supporting-text" style="margin-bottom: 30px;">
//                                          <button onclick="confirmDelete()" type="button" class="mdl-button mdl-js-button mdl-button--raised">
//                                            Delete
//                                          </button>
//                                      </div>
//                                  </div>
//                              </div>
//                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
//                                  <div class="mdl-card__supporting-text">
//                                      <div hidden class="mdl-card__supporting-text" style="margin-bottom: 30px;">
//                                          <button id="delete" name="delete" type="submit" class="mdl-button mdl-js-button mdl-button--raised">
//                                          </button>
//                                      </div>
//                                  </div>
//                              </div>
//                          </div>';
                }

                ?>
            </form>
        </div>
    </section>
</div>