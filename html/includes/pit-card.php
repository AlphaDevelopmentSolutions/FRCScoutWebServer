<div class="mdl-layout__tab-panel is-active" id="overview">
    <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
        <div class="mdl-card mdl-cell mdl-cell--12-col">
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" style="padding-top: 30px;" id="pit-card-form">
                <strong style="padding-left: 40px; padding-top: 10px;">Pre Game</strong>
                <div class="mdl-card__supporting-text">

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeamId ?>" >
                        <label class="mdl-textfield__label" >Team Id</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->CompletedBy ?>" name="completedBy">
                        <label class="mdl-textfield__label" >Scouter</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->DriveStyle ?>" name="driveStyle">
                        <label class="mdl-textfield__label" >Drive Style</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Autonomous</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->AutoExitHabitat ?>" name="autonomousExitHabitat">
                        <label class="mdl-textfield__label" >Exit Habitat</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->AutoHatch ?>" name="autonomousHatchPanelsSecured">
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->AutoCargo ?>" name="autonomousCargoStored">
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeleopHatch ?>" name="teleopHatchPanelsSecured">
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeleopCargo ?>" name="teleopCargoStored">
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->TeleopRocketsComplete ?>" name="teleopRocketsCompleted">
                        <label class="mdl-textfield__label" >Rockets Completed</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">End Game</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->ReturnToHabitat ?>" name="returnedToHabitat">
                        <label class="mdl-textfield__label" >Returned To Habitat</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $pitCard->Notes ?>" name="notes">
                        <label class="mdl-textfield__label" >Notes</label>
                    </div>
                </div>
                <?php

                if(loggedIn()) {
                    echo
                    '<div class="mdl-card__supporting-text">
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <div class="mdl-card__supporting-text">
                                      <div class="mdl-card__supporting-text" style="margin-bottom: 30px;">
                                          <button name="save" type="submit" class="mdl-button mdl-js-button mdl-button--raised">
                                            Save
                                          </button>
                                      </div>
                                  </div>
                              </div>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <div class="mdl-card__supporting-text">
                                      <div class="mdl-card__supporting-text" style="margin-bottom: 30px;">
                                          <button onclick="confirmDelete()" type="button" class="mdl-button mdl-js-button mdl-button--raised">
                                            Delete
                                          </button>
                                      </div>
                                  </div>
                              </div>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                  <div class="mdl-card__supporting-text">
                                      <div hidden class="mdl-card__supporting-text" style="margin-bottom: 30px;">
                                          <button id="delete" name="delete" type="submit" class="mdl-button mdl-js-button mdl-button--raised">
                                          </button>
                                      </div>
                                  </div>
                              </div>
                          </div>';
                }

                ?>
            </form>
        </div>
    </section>
</div>