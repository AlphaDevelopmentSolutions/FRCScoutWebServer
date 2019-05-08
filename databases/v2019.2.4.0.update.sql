ALTER TABLE `scouting_wiredcats5885_ca`.`events`
ADD COLUMN `YearId` VARCHAR(45) NULL DEFAULT '2019' AFTER `EndDate`,
CHANGE COLUMN `Name` `Name` TEXT NULL DEFAULT '' ;