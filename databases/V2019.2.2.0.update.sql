CREATE TABLE `checklist_items` (
`Id` INT NOT NULL AUTO_INCREMENT,
`Title` INT NULL,
`Description` TEXT NULL,
PRIMARY KEY (`Id`));

CREATE TABLE `checklist_item_results` (
`Id` INT NOT NULL AUTO_INCREMENT,
`ChecklistItemId` INT NULL,
`MatchId` VARCHAR(30) NULL,
`Status` VARCHAR(15) NULL,
`CompletedBy` VARCHAR(75) NULL,
`CompletedDate` DATETIME NULL,
PRIMARY KEY (`Id`));