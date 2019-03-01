<div class="mdl-layout__tab-panel is-active" id="overview">
    <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
        <div class="mdl-card mdl-cell mdl-cell--12-col">

            <?php

            $scoutCardIds = array();

            if($allianceColor == 'BLUE')
                $scoutCardIds = Matches::getBlueAllianceScoutCardIds($eventId, $matchId);

            else
                $scoutCardIds = Matches::getRedAllianceScoutCardIds($eventId, $matchId);


            foreach($scoutCardIds AS $scoutCardId)
            {
                $scoutCard = new ScoutCards();
                $scoutCard->load($scoutCardId['Id']);

                $team = new Teams();
                $team->load($scoutCard->TeamId);

                ?>



                <h4 style="padding-left: 40px; padding-top: 10px;"><?php echo $team->Id; ?></h4>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->CompletedBy ?>" >
                        <label class="mdl-textfield__label" >Scouter</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Autonomous</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo (($scoutCard->AutonomousExitHabitat == 1) ? 'Yes' : 'No') ?>" >
                        <label class="mdl-textfield__label" >Exit Habitat</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousHatchPanelsSecured ?>" >
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousHatchPanelsSecuredAttempts ?>" name="autonomousHatchPanelsSecuredAttempts">
                        <label class="mdl-textfield__label" >Hatch Panels Attempts</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousCargoStored ?>" >
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->AutonomousCargoStoredAttempts ?>" name="autonomousCargoStoredAttempts">
                        <label class="mdl-textfield__label" >Cargo Storage Attempts</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopHatchPanelsSecured ?>" >
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopHatchPanelsSecuredAttempts ?>" name="teleopHatchPanelsSecuredAttempts">
                        <label class="mdl-textfield__label" >Hatch Panels Attempts</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopCargoStored ?>" >
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopCargoStoredAttempts ?>" name="teleopCargoStoredAttempts">
                        <label class="mdl-textfield__label" >Cargo Storage Attempts</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->TeleopRocketsCompleted ?>" >
                        <label class="mdl-textfield__label" >Rockets Completed</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">End Game</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->EndGameReturnedToHabitat ?>" >
                        <label class="mdl-textfield__label" >Returned To Habitat</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->EndGameReturnedToHabitatAttempts ?>" name="returnedToHabitatAttempts">
                        <label class="mdl-textfield__label" >Returned To Habitat Attempt</label>
                    </div>


                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->Notes ?>" >
                        <label class="mdl-textfield__label" >Notes</label>
                    </div>
                </div>

            <?php } ?>
        </div>
    </section>
</div>