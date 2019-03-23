ALTER TABLE `scout_cards`
DROP COLUMN `TeleopRocketsCompleted`,
CHANGE COLUMN `BlueAllianceFinalScore` `BlueAllianceFinalScore` INT(11) NULL DEFAULT NULL AFTER `EndGameReturnedToHabitatAttempts`,
CHANGE COLUMN `RedAllianceFinalScore` `RedAllianceFinalScore` INT(11) NULL DEFAULT NULL AFTER `BlueAllianceFinalScore`,
ADD COLUMN `PreGameStartingLevel` INT NULL AFTER `CompletedBy`,
ADD COLUMN `PreGameStartingPosition` VARCHAR(7) NULL AFTER `PreGameStartingLevel`,
ADD COLUMN `PreGameStartingPiece` VARCHAR(6) NULL AFTER `PreGameStartingPosition`,
ADD COLUMN `AutonomousHatchPanelsPickedUp` INT NULL AFTER `AutonomousExitHabitat`,
ADD COLUMN `AutonomousCargoPickedUp` INT NULL AFTER `AutonomousHatchPanelsSecured`,
ADD COLUMN `TeleopHatchPanelsPickedUp` INT NULL AFTER `AutonomousCargoStored`,
ADD COLUMN `TeleopCargoPickedUp` INT NULL AFTER `TeleopHatchPanelsSecured`,
ADD COLUMN `DefenseRating` INT NULL AFTER `RedAllianceFinalScore`,
ADD COLUMN `OffenseRating` INT NULL AFTER `DefenseRating`,
ADD COLUMN `DriveRating` INT NULL AFTER `OffenseRating`,
CHANGE COLUMN `AutonomousHatchPanelsSecuredAttempts` `AutonomousHatchPanelsSecuredAttempts` INT(11) NULL DEFAULT NULL AFTER `AutonomousHatchPanelsPickedUp`,
CHANGE COLUMN `AutonomousCargoStoredAttempts` `AutonomousCargoStoredAttempts` INT(11) NULL DEFAULT NULL AFTER `AutonomousCargoPickedUp`,
CHANGE COLUMN `TeleopHatchPanelsSecuredAttempts` `TeleopHatchPanelsSecuredAttempts` INT(11) NULL DEFAULT NULL AFTER `TeleopHatchPanelsPickedUp`,
CHANGE COLUMN `TeleopCargoStoredAttempts` `TeleopCargoStoredAttempts` INT(11) NULL DEFAULT NULL AFTER `TeleopCargoPickedUp`;

update scout_cards set autonomousexithabitat = 0 WHERE autonomousexithabitat = 'No';
update scout_cards set autonomousexithabitat = substr(autonomousexithabitat, 7) WHERE autonomousexithabitat LIKE '%Level%';
update scout_cards set pregamestartinglevel = autonomousexithabitat where autonomousexithabitat > 0;
update scout_cards set autonomousexithabitat = 1 where autonomousexithabitat > 0;

ALTER TABLE `scout_cards`
CHANGE COLUMN `AutonomousExitHabitat` `AutonomousExitHabitat` INT NULL DEFAULT NULL;

update scout_cards set endgamereturnedtohabitat = substr(endgamereturnedtohabitat, 7) WHERE endgamereturnedtohabitat LIKE '%Level%';
update scout_cards set endgamereturnedtohabitat = 0 WHERE endgamereturnedtohabitat = 'No';
update scout_cards set endgamereturnedtohabitatattempts = substr(endgamereturnedtohabitatattempts, 7) WHERE endgamereturnedtohabitatattempts LIKE '%Level%';
update scout_cards set endgamereturnedtohabitatattempts = 0 WHERE endgamereturnedtohabitatattempts = 'No';

ALTER TABLE `pit_cards`
ADD COLUMN `RobotWeight` VARCHAR(45) NULL AFTER `DriveStyle`,
DROP COLUMN `TeleopRocketsComplete`;

ALTER TABLE `robot_media`
CHANGE COLUMN `RobotId` `TeamId` INT(11) NULL DEFAULT NULL,
CHANGE COLUMN `Id` `Id` INT(11) NOT NULL AUTO_INCREMENT ;

