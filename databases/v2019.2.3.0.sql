--
-- Table structure for table `checklist_item_results`
--

DROP TABLE IF EXISTS `checklist_item_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklist_item_results` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ChecklistItemId` int(11) DEFAULT '-1',
  `MatchId` varchar(30) DEFAULT '',
  `Status` varchar(15) DEFAULT 'INCOMPLETE',
  `CompletedBy` varchar(75) DEFAULT '',
  `CompletedDate` datetime DEFAULT '2019-01-01 00:00:00',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `checklist_items`
--

DROP TABLE IF EXISTS `checklist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklist_items` (
  `Id` int(11) NOT NULL,
  `Title` text,
  `Description` text,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_team_list`
--

DROP TABLE IF EXISTS `event_team_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_team_list` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TeamId` int(11) DEFAULT '-1',
  `EventId` varchar(15) DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1625 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `BlueAllianceId` varchar(45) DEFAULT '',
  `Name` text,
  `City` varchar(45) DEFAULT '',
  `StateProvince` varchar(45) DEFAULT '',
  `Country` varchar(45) DEFAULT '',
  `StartDate` datetime DEFAULT '2019-01-01 00:00:00',
  `EndDate` datetime DEFAULT '2019-01-01 00:00:00',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matches` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Date` datetime DEFAULT '2019-01-01 00:00:00',
  `EventId` varchar(45) DEFAULT '',
  `Key` varchar(45) DEFAULT '',
  `MatchType` varchar(5) DEFAULT '',
  `SetNumber` int(11) DEFAULT '0',
  `MatchNumber` int(11) DEFAULT '0',
  `BlueAllianceTeamOneId` int(11) DEFAULT '0',
  `BlueAllianceTeamTwoId` int(11) DEFAULT '0',
  `BlueAllianceTeamThreeId` int(11) DEFAULT '0',
  `RedAllianceTeamOneId` int(11) DEFAULT '0',
  `RedAllianceTeamTwoId` int(11) DEFAULT '0',
  `RedAllianceTeamThreeId` int(11) DEFAULT '0',
  `BlueAllianceScore` int(11) DEFAULT '0',
  `RedAllianceScore` int(11) DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3073 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pit_cards`
--

DROP TABLE IF EXISTS `pit_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pit_cards` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TeamId` int(11) DEFAULT '0',
  `EventId` varchar(45) DEFAULT '',
  `DriveStyle` varchar(45) DEFAULT '',
  `RobotWeight` varchar(45) DEFAULT '',
  `RobotLength` varchar(45) DEFAULT '',
  `RobotWidth` varchar(45) DEFAULT '',
  `RobotHeight` varchar(45) DEFAULT '',
  `AutoExitHabitat` varchar(45) DEFAULT '',
  `AutoHatch` varchar(45) DEFAULT '',
  `AutoCargo` varchar(45) DEFAULT '',
  `TeleopHatch` varchar(45) DEFAULT '',
  `TeleopCargo` varchar(45) DEFAULT '',
  `ReturnToHabitat` varchar(45) DEFAULT '',
  `Notes` varchar(100) DEFAULT '',
  `CompletedBy` varchar(45) DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `robot_media`
--

DROP TABLE IF EXISTS `robot_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `robot_media` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TeamId` int(11) DEFAULT '0',
  `FileURI` varchar(45) DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `robots`
--

DROP TABLE IF EXISTS `robots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `robots` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT '',
  `TeamId` int(11) DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scout_cards`
--

DROP TABLE IF EXISTS `scout_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scout_cards` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `MatchId` varchar(45) DEFAULT '',
  `TeamId` int(11) DEFAULT '9999999',
  `EventId` varchar(45) DEFAULT '',
  `AllianceColor` varchar(4) DEFAULT '',
  `CompletedBy` varchar(45) DEFAULT '',
  `PreGameStartingLevel` int(11) DEFAULT '0',
  `PreGameStartingPosition` varchar(7) DEFAULT '',
  `PreGameStartingPiece` varchar(6) DEFAULT '',
  `AutonomousExitHabitat` int(11) DEFAULT '0',
  `AutonomousHatchPanelsPickedUp` int(11) DEFAULT '0',
  `AutonomousHatchPanelsSecuredAttempts` int(11) DEFAULT '0',
  `AutonomousHatchPanelsSecured` int(11) DEFAULT '0',
  `AutonomousCargoPickedUp` int(11) DEFAULT '0',
  `AutonomousCargoStoredAttempts` int(11) DEFAULT '0',
  `AutonomousCargoStored` int(11) DEFAULT '0',
  `TeleopHatchPanelsPickedUp` int(11) DEFAULT '0',
  `TeleopHatchPanelsSecuredAttempts` int(11) DEFAULT '0',
  `TeleopHatchPanelsSecured` int(11) DEFAULT '0',
  `TeleopCargoPickedUp` int(11) DEFAULT '0',
  `TeleopCargoStoredAttempts` int(11) DEFAULT '0',
  `TeleopCargoStored` int(11) DEFAULT '0',
  `EndGameReturnedToHabitat` int(11) DEFAULT '0',
  `EndGameReturnedToHabitatAttempts` int(11) DEFAULT '0',
  `BlueAllianceFinalScore` int(11) DEFAULT '0',
  `RedAllianceFinalScore` int(11) DEFAULT '0',
  `DefenseRating` int(11) DEFAULT '0',
  `OffenseRating` int(11) DEFAULT '0',
  `DriveRating` int(11) DEFAULT '0',
  `Notes` varchar(250) DEFAULT '',
  `CompletedDate` datetime DEFAULT '2019-01-01 00:00:00',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1110 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT '',
  `City` varchar(45) DEFAULT '',
  `StateProvince` varchar(45) DEFAULT '',
  `Country` varchar(45) DEFAULT '',
  `RookieYear` int(11) DEFAULT '0',
  `FacebookURL` varchar(100) DEFAULT '',
  `TwitterURL` varchar(100) DEFAULT '',
  `InstagramURL` varchar(100) DEFAULT '',
  `YoutubeURL` varchar(100) DEFAULT '',
  `WebsiteURL` varchar(100) DEFAULT '',
  `ImageFileURI` varchar(100) DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=7801 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(45) DEFAULT '',
  `LastName` varchar(45) DEFAULT '',
  `UserName` varchar(45) DEFAULT '',
  `Password` varchar(55) DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-05 20:21:04
