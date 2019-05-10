ALTER TABLE `events`
ADD COLUMN `YearId` VARCHAR(45) NULL DEFAULT '2019' AFTER `EndDate`,
CHANGE COLUMN `Name` `Name` VARCHAR(3000) NULL DEFAULT '' ;

CREATE TABLE `robot_info` (
`Id` INT NOT NULL AUTO_INCREMENT,
`YearId` INT NULL DEFAULT 2019,
`EventId` VARCHAR(45) NULL DEFAULT '',
`TeamId` INT NULL DEFAULT 0,
`PropertyState` TEXT NULL DEFAULT '',
`PropertyKey` TEXT NULL DEFAULT '',
`PropertyValue` TEXT NULL DEFAULT '',
PRIMARY KEY (`Id`));

ALTER TABLE `checklist_items`
CHANGE COLUMN `Title` `Title` VARCHAR(3000) NULL DEFAULT '' ,
CHANGE COLUMN `Description` `Description` VARCHAR(3000) NULL DEFAULT '' ;

ALTER TABLE `events`
CHANGE COLUMN `Name` `Name` VARCHAR(3000) NULL DEFAULT '' ;

ALTER TABLE `years`
CHANGE COLUMN `Name` `Name` VARCHAR(3000) NULL DEFAULT '' ;


