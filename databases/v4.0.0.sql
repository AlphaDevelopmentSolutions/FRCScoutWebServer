--
-- Table structure for table `checklist_item_results`
--

DROP TABLE IF EXISTS `checklist_item_results`;
CREATE TABLE `checklist_item_results` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ChecklistItemId` int(11) DEFAULT '-1',
  `MatchId` varchar(30) DEFAULT '',
  `Status` varchar(15) DEFAULT 'INCOMPLETE',
  `CompletedBy` varchar(75) DEFAULT '',
  `CompletedDate` datetime DEFAULT '2019-01-01 00:00:00',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `checklist_items`
--

DROP TABLE IF EXISTS `checklist_items`;
CREATE TABLE `checklist_items` (
  `Id` int(11) NOT NULL,
  `YearId` int(11) DEFAULT '2019',
  `Title` varchar(3000) DEFAULT '',
  `Description` varchar(3000) DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Key` varchar(3000) DEFAULT NULL,
  `Value` varchar(3000) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `robot_info`
--

DROP TABLE IF EXISTS `robot_info`;
CREATE TABLE `robot_info` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `YearId` int(11) DEFAULT '2019',
  `EventId` varchar(45) DEFAULT '',
  `TeamId` int(11) DEFAULT '0',
  `PropertyValue` varchar(3000) DEFAULT '',
  `PropertyKeyId` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=8617 DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `robot_info_keys`
--

DROP TABLE IF EXISTS `robot_info_keys`;
CREATE TABLE `robot_info_keys` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `YearId` int(11) DEFAULT '2019',
  `KeyState` varchar(45) DEFAULT '',
  `KeyName` varchar(45) DEFAULT '',
  `SortOrder` int(11) DEFAULT '1',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `robot_media`
--

DROP TABLE IF EXISTS `robot_media`;
CREATE TABLE `robot_media` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `YearId` int(11) DEFAULT '2019',
  `EventId` varchar(45) DEFAULT '',
  `TeamId` int(11) DEFAULT '0',
  `FileURI` varchar(45) DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `scout_card_info`
--

DROP TABLE IF EXISTS `scout_card_info`;
CREATE TABLE `scout_card_info` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `YearId` int(11) DEFAULT '2019',
  `EventId` varchar(45) DEFAULT '',
  `MatchId` varchar(45) DEFAULT '',
  `TeamId` int(11) DEFAULT '1',
  `CompletedBy` varchar(200) DEFAULT '',
  `PropertyValue` varchar(2000) DEFAULT '',
  `PropertyKeyId` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=69433 DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `scout_card_info_keys`
--

DROP TABLE IF EXISTS `scout_card_info_keys`;
CREATE TABLE `scout_card_info_keys` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `YearId` int(11) DEFAULT '2019',
  `KeyState` varchar(45) DEFAULT '',
  `KeyName` varchar(45) DEFAULT '',
  `SortOrder` int(11) DEFAULT '1',
  `MinValue` int(11) DEFAULT NULL,
  `MaxValue` int(11) DEFAULT NULL,
  `NullZeros` BIT(1) DEFAULT b'0',
  `IncludeInStats` BIT(1) DEFAULT b'0',
  `DataType` enum('INT','BOOL','TEXT') DEFAULT 'INT',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(45) DEFAULT '',
  `LastName` varchar(45) DEFAULT '',
  `UserName` varchar(45) DEFAULT '',
  `Password` varchar(200) DEFAULT '',
  `IsAdmin` BIT(1) DEFAULT b'0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;