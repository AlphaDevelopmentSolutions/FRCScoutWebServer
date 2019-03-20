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

                $totalAutoHatchSecured = 0;
                $totalAutoHatchDropped = 0;
                $totalAutoCargoSecured = 0;
                $totalAutoCargoDropped = 0;

                $totalTeleopHatchSecured = 0;
                $totalTeleopHatchDropped = 0;
                $totalTeleopCargoSecured = 0;
                $totalTeleopCargoDropped = 0;

                //calc totals from match item actions
                foreach(MatchItemActions::getMatchItemActionsForScoutCard($scoutCard->Id) as $matchItemAction)
                {
                    //calc auto
                    if($matchItemAction['MatchState'] == MatchState::AUTO)
                    {
                        //calc hatches
                        if($matchItemAction['ItemType'] == ItemType::HATCH)
                        {
                            $totalAutoHatchSecured += (($matchItemAction['Action'] == Action::SECURED) ? 1 : 0);
                            $totalAutoHatchDropped += (($matchItemAction['Action'] == Action::DROPPED) ? 1 : 0);
                        }
                        //calc cargo
                        else if($matchItemAction['ItemType'] == ItemType::CARGO)
                        {
                            $totalAutoCargoSecured += (($matchItemAction['Action'] == Action::SECURED) ? 1 : 0);
                            $totalAutoCargoDropped += (($matchItemAction['Action'] == Action::DROPPED) ? 1 : 0);
                        }
                    }
                    //calc teleop
                    else if($matchItemAction['MatchState'] == MatchState::TELEOP)
                    {
                        //calc hatches
                        if($matchItemAction['ItemType'] == ItemType::HATCH)
                        {
                            $totalTeleopHatchSecured += (($matchItemAction['Action'] == Action::SECURED) ? 1 : 0);
                            $totalTeleopHatchDropped += (($matchItemAction['Action'] == Action::DROPPED) ? 1 : 0);
                        }
                        //calc cargo
                        else if($matchItemAction['ItemType'] == ItemType::CARGO)
                        {
                            $totalTeleopCargoSecured += (($matchItemAction['Action'] == Action::SECURED) ? 1 : 0);
                            $totalTeleopCargoDropped += (($matchItemAction['Action'] == Action::DROPPED) ? 1 : 0);
                        }
                    }
                }

                ?>



                <h4 style="padding-left: 40px; padding-top: 10px;"><?php echo $team->Id; ?></h4>
                <div class="mdl-card__supporting-text">

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->CompletedBy ?>" name="completedBy">
                        <label class="mdl-textfield__label" >Scouter</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $scoutCard->PreGameStartingPosition ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Starting Position</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo ((empty($scoutCard->PreGameStartingLevel)) ? '' : 'Level ' . $scoutCard->PreGameStartingLevel) ?>" name="redAllianceScore">
                        <label class="mdl-textfield__label" >Starting Level</label>
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
                        <input class="mdl-textfield__input" type="text" value="<?php echo $totalAutoHatchSecured ?>" name="autonomousHatchPanelsSecured">
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $totalAutoHatchDropped ?>" name="autonomousHatchPanelsSecuredAttempts">
                        <label class="mdl-textfield__label" >Hatch Panels Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $totalAutoCargoSecured ?>" name="autonomousCargoStored">
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $totalAutoCargoDropped ?>" name="autonomousCargoStoredAttempts">
                        <label class="mdl-textfield__label" >Cargo Storage Dropped</label>
                    </div>
                </div>

                <strong style="padding-left: 40px; padding-top: 10px;">Teleop</strong>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $totalTeleopHatchSecured ?>" name="teleopHatchPanelsSecured">
                        <label class="mdl-textfield__label" >Hatch Panels Secured</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $totalTeleopHatchDropped ?>" name="teleopHatchPanelsSecuredAttempts">
                        <label class="mdl-textfield__label" >Hatch Panels Dropped</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $totalTeleopCargoSecured ?>" name="teleopCargoStored">
                        <label class="mdl-textfield__label" >Cargo Stored</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" value="<?php echo $totalTeleopCargoDropped ?>" name="teleopCargoStoredAttempts">
                        <label class="mdl-textfield__label" >Cargo Storage Dropped</label>
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

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <textarea class="mdl-textfield__input" type="text" rows="3" name="notes" ><?php echo $scoutCard->Notes ?></textarea>
                        <label class="mdl-textfield__label" >Notes</label>
                    </div>
                </div>

            <?php } ?>
        </div>
    </section>
</div>