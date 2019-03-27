<div class="mdl-layout__tab-panel is-active" id="overview">
    <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
        <div class="mdl-card mdl-cell mdl-cell--12-col">
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" style="padding-top: 30px;" id="scout-card-form">
                <strong style="padding-left: 40px;">Pre Game</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->TeamId) ? '&nbsp' : $scoutCard->TeamId ?>" name="teamId" >
                        <label class="mdl-textfield__label">Team Id</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->CompletedBy) ? '&nbsp' : $scoutCard->CompletedBy ?>" name="completedBy">
                        <label class="mdl-textfield__label" >Scouter</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->MatchId) ? '&nbsp' : $scoutCard->MatchId ?>" name="matchId">
                        <label class="mdl-textfield__label" >Match Number</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->AllianceColor) ? '&nbsp' : $scoutCard->AllianceColor ?>" name="allianceColor">
                        <label class="mdl-textfield__label" >AllianceColor</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo (empty($scoutCard->PreGameStartingLevel)) ? 'nbsp' : 'Level ' . $scoutCard->PreGameStartingLevel ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Starting Level</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->PreGameStartingPosition) ? '&nbsp' : $scoutCard->PreGameStartingPosition ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Starting Position</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->PreGameStartingPiece) ? '&nbsp' : $scoutCard->PreGameStartingPiece ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Starting Piece</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Autonomous</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo (($scoutCard->AutonomousExitHabitat == 1) ? 'Yes' : 'No')?>" name="autonomousExitHabitat">
                        <label class="mdl-textfield__label" >Exit Habitat</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->AutonomousHatchPanelsPickedUp) ? 0 : $scoutCard->AutonomousHatchPanelsPickedUp ?>" name="autonomousHatchPanelsPickedUp">
                        <label class="mdl-textfield__label" >Hatch Panels Picked Up</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->AutonomousHatchPanelsSecuredAttempts) ? 0 : $scoutCard->AutonomousHatchPanelsSecuredAttempts ?>" name="autonomousHatchPanelsSecuredAttempts">
                        <label class="mdl-textfield__label" >Hatch Panels Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->AutonomousHatchPanelsSecured) ? 0 : $scoutCard->AutonomousHatchPanelsSecured ?>" name="autonomousHatchPanelsSecured">
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo round(($scoutCard->AutonomousHatchPanelsSecured / $scoutCard->AutonomousHatchPanelsPickedUp) * 100, 2) ?>%">
                        <label class="mdl-textfield__label" >Hatch Stored / Pickup %</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->AutonomousCargoPickedUp) ? 0 : $scoutCard->AutonomousCargoPickedUp ?>" name="autonomousCargoPickedUp">
                        <label class="mdl-textfield__label" >Cargo Picked Up</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->AutonomousCargoStoredAttempts) ? 0 : $scoutCard->AutonomousCargoStoredAttempts ?>" name="autonomousCargoStoredAttempts">
                        <label class="mdl-textfield__label" >Cargo Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->AutonomousCargoStored) ?  0 : $scoutCard->AutonomousCargoStored ?>" name="autonomousCargoStored">
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo round(($scoutCard->AutonomousCargoStored / $scoutCard->AutonomousCargoPickedUp) * 100, 2) ?>%">
                        <label class="mdl-textfield__label" >Cargo Stored / Pickup %</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->TeleopHatchPanelsPickedUp) ? 0 : $scoutCard->TeleopHatchPanelsPickedUp ?>" name="TeleopHatchPanelsPickedUp">
                        <label class="mdl-textfield__label" >Hatch Panels Picked Up</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->TeleopHatchPanelsSecuredAttempts) ? 0 : $scoutCard->TeleopHatchPanelsSecuredAttempts ?>" name="TeleopHatchPanelsSecuredAttempts">
                        <label class="mdl-textfield__label" >Hatch Panels Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->TeleopHatchPanelsSecured) ? 0 : $scoutCard->TeleopHatchPanelsSecured ?>" name="TeleopHatchPanelsSecured">
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo round(($scoutCard->TeleopHatchPanelsSecured / $scoutCard->TeleopHatchPanelsPickedUp) * 100, 2) ?>%">
                        <label class="mdl-textfield__label" >Hatch Stored / Pickup %</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->TeleopCargoPickedUp) ? 0 : $scoutCard->TeleopCargoPickedUp ?>" name="teleopCargoPickedUp">
                        <label class="mdl-textfield__label" >Cargo Picked Up</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->TeleopCargoStoredAttempts) ? 0 : $scoutCard->TeleopCargoStoredAttempts ?>" name="teleopCargoStoreddAttempts">
                        <label class="mdl-textfield__label" >Cargo Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->TeleopCargoStored) ? 0 : $scoutCard->TeleopCargoStored ?>" name="teleopCargoStored">
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo round(($scoutCard->TeleopCargoStored / $scoutCard->TeleopCargoPickedUp) * 100, 2) ?>%">
                        <label class="mdl-textfield__label" >Cargo Stored / Pickup %</label>
                    </div>

                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">End Game</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->EndGameReturnedToHabitat) ? 'No' : 'Level ' . $scoutCard->EndGameReturnedToHabitat ?>" name="returnedToHabitat">
                        <label class="mdl-textfield__label" >Returned To Habitat</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->EndGameReturnedToHabitatAttempts) ? 'No' : 'Level ' . $scoutCard->EndGameReturnedToHabitatAttempts ?>" name="returnedToHabitatAttempts">
                        <label class="mdl-textfield__label" >Returned To Habitat Failed  Attempt</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Post Game</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->BlueAllianceFinalScore) ? '&nbsp' : $scoutCard->BlueAllianceFinalScore ?>" name="blueAllianceScore">
                        <label class="mdl-textfield__label" >Blue Alliance Score</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php echo empty($scoutCard->RedAllianceFinalScore) ? '&nbsp' : $scoutCard->RedAllianceFinalScore ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Red Alliance Score</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php if($scoutCard->DefenseRating == 0) echo '&nbsp'; else for($i = 0; $i < $scoutCard->DefenseRating; $i++) echo "&#9733;"?>" name="defenseRating">
                        <label class="mdl-textfield__label" >Defense Rating</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php if($scoutCard->OffenseRating == 0) echo '&nbsp'; else for($i = 0; $i < $scoutCard->OffenseRating; $i++) echo "&#9733;"?>" name="offenseRating">
                        <label class="mdl-textfield__label" >Offense Rating</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" value="<?php if($scoutCard->DriveRating == 0) echo '&nbsp'; else for($i = 0; $i < $scoutCard->DriveRating; $i++) echo "&#9733;"?>" name="driveRating">
                        <label class="mdl-textfield__label" >Drive Rating</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <textarea <?php echo ((loggedIn()) ? "" : "disabled") ?> class="mdl-textfield__input" type="text" rows="3" name="notes" ><?php echo empty($scoutCard->Notes) ? '&nbsp' : $scoutCard->Notes ?></textarea>
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