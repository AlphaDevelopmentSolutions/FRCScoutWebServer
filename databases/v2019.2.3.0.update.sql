ALTER TABLE `scout_cards`
CHANGE COLUMN `MatchId` `MatchId` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `TeamId` `TeamId` INT(11) NULL DEFAULT 9999999 ,
CHANGE COLUMN `EventId` `EventId` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `AllianceColor` `AllianceColor` VARCHAR(4) NULL DEFAULT '' ,
CHANGE COLUMN `CompletedBy` `CompletedBy` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `PreGameStartingLevel` `PreGameStartingLevel` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `PreGameStartingPosition` `PreGameStartingPosition` VARCHAR(7) NULL DEFAULT '' ,
CHANGE COLUMN `PreGameStartingPiece` `PreGameStartingPiece` VARCHAR(6) NULL DEFAULT '' ,
CHANGE COLUMN `AutonomousExitHabitat` `AutonomousExitHabitat` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `AutonomousHatchPanelsPickedUp` `AutonomousHatchPanelsPickedUp` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `AutonomousHatchPanelsSecuredAttempts` `AutonomousHatchPanelsSecuredAttempts` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `AutonomousHatchPanelsSecured` `AutonomousHatchPanelsSecured` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `AutonomousCargoPickedUp` `AutonomousCargoPickedUp` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `AutonomousCargoStoredAttempts` `AutonomousCargoStoredAttempts` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `AutonomousCargoStored` `AutonomousCargoStored` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `TeleopHatchPanelsPickedUp` `TeleopHatchPanelsPickedUp` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `TeleopHatchPanelsSecuredAttempts` `TeleopHatchPanelsSecuredAttempts` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `TeleopHatchPanelsSecured` `TeleopHatchPanelsSecured` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `TeleopCargoPickedUp` `TeleopCargoPickedUp` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `TeleopCargoStoredAttempts` `TeleopCargoStoredAttempts` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `TeleopCargoStored` `TeleopCargoStored` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `EndGameReturnedToHabitat` `EndGameReturnedToHabitat` INT NULL DEFAULT 0 ,
CHANGE COLUMN `EndGameReturnedToHabitatAttempts` `EndGameReturnedToHabitatAttempts` INT NULL DEFAULT 0 ,
CHANGE COLUMN `BlueAllianceFinalScore` `BlueAllianceFinalScore` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `RedAllianceFinalScore` `RedAllianceFinalScore` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `DefenseRating` `DefenseRating` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `OffenseRating` `OffenseRating` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `DriveRating` `DriveRating` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `Notes` `Notes` VARCHAR(250) NULL DEFAULT '' ,
CHANGE COLUMN `CompletedDate` `CompletedDate` DATETIME NULL DEFAULT '2019-01-01 00:00:00' ;

ALTER TABLE `checklist_item_results`
CHANGE COLUMN `ChecklistItemId` `ChecklistItemId` INT(11) NULL DEFAULT -1 ,
CHANGE COLUMN `MatchId` `MatchId` VARCHAR(30) NULL DEFAULT '' ,
CHANGE COLUMN `Status` `Status` VARCHAR(15) NULL DEFAULT 'INCOMPLETE' ,
CHANGE COLUMN `CompletedBy` `CompletedBy` VARCHAR(75) NULL DEFAULT '' ,
CHANGE COLUMN `CompletedDate` `CompletedDate` DATETIME NULL DEFAULT '2019-01-01 00:00:00'

ALTER TABLE `checklist_items`
CHANGE COLUMN `Id` `Id` INT(11) NOT NULL ,
CHANGE COLUMN `Title` `Title` TEXT NULL DEFAULT '' ,
CHANGE COLUMN `Description` `Description` TEXT NULL DEFAULT '' ;

ALTER TABLE `events`
CHANGE COLUMN `BlueAllianceId` `BlueAllianceId` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `Name` `Name` TEXT NULL DEFAULT '' ,
CHANGE COLUMN `City` `City` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `StateProvince` `StateProvince` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `Country` `Country` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `StartDate` `StartDate` DATETIME NULL DEFAULT '2019-01-01 00:00:00' ,
CHANGE COLUMN `EndDate` `EndDate` DATETIME NULL DEFAULT '2019-01-01 00:00:00' ;

ALTER TABLE `event_team_list`
CHANGE COLUMN `TeamId` `TeamId` INT(11) NULL DEFAULT -1 ,
CHANGE COLUMN `EventId` `EventId` VARCHAR(15) NULL DEFAULT '' ;


ALTER TABLE `matches`
CHANGE COLUMN `Date` `Date` DATETIME NULL DEFAULT '2019-01-01 00:00:00' ,
CHANGE COLUMN `EventId` `EventId` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `Key` `Key` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `MatchType` `MatchType` VARCHAR(5) NULL DEFAULT '' ,
CHANGE COLUMN `SetNumber` `SetNumber` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `MatchNumber` `MatchNumber` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `BlueAllianceTeamOneId` `BlueAllianceTeamOneId` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `BlueAllianceTeamTwoId` `BlueAllianceTeamTwoId` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `BlueAllianceTeamThreeId` `BlueAllianceTeamThreeId` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `RedAllianceTeamOneId` `RedAllianceTeamOneId` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `RedAllianceTeamTwoId` `RedAllianceTeamTwoId` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `RedAllianceTeamThreeId` `RedAllianceTeamThreeId` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `BlueAllianceScore` `BlueAllianceScore` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `RedAllianceScore` `RedAllianceScore` INT(11) NULL DEFAULT 0 ;

ALTER TABLE `pit_cards`
CHANGE COLUMN `TeamId` `TeamId` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `EventId` `EventId` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `DriveStyle` `DriveStyle` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `RobotWeight` `RobotWeight` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `RobotLength` `RobotLength` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `RobotWidth` `RobotWidth` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `RobotHeight` `RobotHeight` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `AutoExitHabitat` `AutoExitHabitat` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `AutoHatch` `AutoHatch` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `AutoCargo` `AutoCargo` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `TeleopHatch` `TeleopHatch` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `TeleopCargo` `TeleopCargo` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `ReturnToHabitat` `ReturnToHabitat` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `Notes` `Notes` VARCHAR(100) NULL DEFAULT '' ,
CHANGE COLUMN `CompletedBy` `CompletedBy` VARCHAR(45) NULL DEFAULT '' ;

ALTER TABLE `robot_media`
CHANGE COLUMN `TeamId` `TeamId` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `FileURI` `FileURI` VARCHAR(45) NULL DEFAULT '' ;

ALTER TABLE `robots`
CHANGE COLUMN `Name` `Name` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `TeamId` `TeamId` INT(11) NULL DEFAULT 0 ;

ALTER TABLE `teams`
CHANGE COLUMN `Name` `Name` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `City` `City` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `StateProvince` `StateProvince` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `Country` `Country` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `RookieYear` `RookieYear` INT(11) NULL DEFAULT 0 ,
CHANGE COLUMN `FacebookURL` `FacebookURL` VARCHAR(100) NULL DEFAULT '' ,
CHANGE COLUMN `TwitterURL` `TwitterURL` VARCHAR(100) NULL DEFAULT '' ,
CHANGE COLUMN `InstagramURL` `InstagramURL` VARCHAR(100) NULL DEFAULT '' ,
CHANGE COLUMN `YoutubeURL` `YoutubeURL` VARCHAR(100) NULL DEFAULT '' ,
CHANGE COLUMN `WebsiteURL` `WebsiteURL` VARCHAR(100) NULL DEFAULT '' ,
CHANGE COLUMN `ImageFileURI` `ImageFileURI` VARCHAR(100) NULL DEFAULT '' ;

ALTER TABLE `users`
CHANGE COLUMN `FirstName` `FirstName` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `LastName` `LastName` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `UserName` `UserName` VARCHAR(45) NULL DEFAULT '' ,
CHANGE COLUMN `Password` `Password` VARCHAR(55) NULL DEFAULT '' ;