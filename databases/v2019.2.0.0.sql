--
-- Table structure for table `event_team_list`
--

DROP TABLE IF EXISTS `event_team_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_team_list` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TeamId` int(11) DEFAULT NULL,
  `EventId` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=397 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `BlueAllianceId` varchar(45) DEFAULT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `StateProvince` varchar(45) DEFAULT NULL,
  `Country` varchar(45) DEFAULT NULL,
  `StartDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matches` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Date` datetime DEFAULT NULL,
  `BlueAllianceTeamOneId` int(11) DEFAULT NULL,
  `BlueAllianceTeamTwoId` int(11) DEFAULT NULL,
  `BlueAllianceTeamThreeId` int(11) DEFAULT NULL,
  `BlueAllianceTeamOneScoutCardId` int(11) DEFAULT NULL,
  `BlueAllianceTeamTwoScoutCardId` int(11) DEFAULT NULL,
  `BlueAllianceTeamThreeScoutCardId` int(11) DEFAULT NULL,
  `BlueAllianceScore` int(11) DEFAULT NULL,
  `RedAllianceScore` int(11) DEFAULT NULL,
  `RedAllianceTeamOneId` int(11) DEFAULT NULL,
  `RedAllianceTeamTwoId` int(11) DEFAULT NULL,
  `RedAllianceTeamThreeId` int(11) DEFAULT NULL,
  `RedAllianceTeamOneScoutCardId` int(11) DEFAULT NULL,
  `RedAllianceTeamTwoScoutCardId` int(11) DEFAULT NULL,
  `RedAllianceTeamThreeScoutCardId` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pit_cards`
--

DROP TABLE IF EXISTS `pit_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pit_cards` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TeamId` int(11) DEFAULT NULL,
  `EventId` varchar(45) DEFAULT NULL,
  `DriveStyle` varchar(45) DEFAULT NULL,
  `RobotWeight` varchar(45) DEFAULT NULL,
  `RobotLength` varchar(45) DEFAULT NULL,
  `RobotWidth` varchar(45) DEFAULT NULL,
  `RobotHeight` varchar(45) DEFAULT NULL,
  `AutoExitHabitat` varchar(45) DEFAULT NULL,
  `AutoHatch` varchar(45) DEFAULT NULL,
  `AutoCargo` varchar(45) DEFAULT NULL,
  `TeleopHatch` varchar(45) DEFAULT NULL,
  `TeleopCargo` varchar(45) DEFAULT NULL,
  `ReturnToHabitat` varchar(45) DEFAULT NULL,
  `Notes` varchar(100) DEFAULT NULL,
  `CompletedBy` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `robot_media`
--

DROP TABLE IF EXISTS `robot_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `robot_media` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `TeamId` int(11) DEFAULT NULL,
  `FileURI` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `robots`
--

DROP TABLE IF EXISTS `robots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `robots` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `TeamId` int(11) DEFAULT NULL,
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
  `MatchId` int(11) DEFAULT NULL,
  `TeamId` int(11) DEFAULT NULL,
  `EventId` varchar(45) DEFAULT NULL,
  `AllianceColor` varchar(4) DEFAULT NULL,
  `CompletedBy` varchar(45) DEFAULT NULL,
  `PreGameStartingLevel` int(11) DEFAULT NULL,
  `PreGameStartingPosition` varchar(7) DEFAULT NULL,
  `PreGameStartingPiece` varchar(6) DEFAULT NULL,
  `AutonomousExitHabitat` int(11) DEFAULT NULL,
  `AutonomousHatchPanelsPickedUp` int(11) DEFAULT NULL,
  `AutonomousHatchPanelsSecuredAttempts` int(11) DEFAULT NULL,
  `AutonomousHatchPanelsSecured` int(11) DEFAULT NULL,
  `AutonomousCargoPickedUp` int(11) DEFAULT NULL,
  `AutonomousCargoStoredAttempts` int(11) DEFAULT NULL,
  `AutonomousCargoStored` int(11) DEFAULT NULL,
  `TeleopHatchPanelsPickedUp` int(11) DEFAULT NULL,
  `TeleopHatchPanelsSecuredAttempts` int(11) DEFAULT NULL,
  `TeleopHatchPanelsSecured` int(11) DEFAULT NULL,
  `TeleopCargoPickedUp` int(11) DEFAULT NULL,
  `TeleopCargoStoredAttempts` int(11) DEFAULT NULL,
  `TeleopCargoStored` int(11) DEFAULT NULL,
  `EndGameReturnedToHabitat` varchar(10) DEFAULT NULL,
  `EndGameReturnedToHabitatAttempts` varchar(10) DEFAULT NULL,
  `BlueAllianceFinalScore` int(11) DEFAULT NULL,
  `RedAllianceFinalScore` int(11) DEFAULT NULL,
  `DefenseRating` int(11) DEFAULT NULL,
  `OffenseRating` int(11) DEFAULT NULL,
  `DriveRating` int(11) DEFAULT NULL,
  `Notes` varchar(250) DEFAULT NULL,
  `CompletedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=432 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `StateProvince` varchar(45) DEFAULT NULL,
  `Country` varchar(45) DEFAULT NULL,
  `RookieYear` int(11) DEFAULT NULL,
  `FacebookURL` varchar(100) DEFAULT NULL,
  `TwitterURL` varchar(100) DEFAULT NULL,
  `InstagramURL` varchar(100) DEFAULT NULL,
  `YoutubeURL` varchar(100) DEFAULT NULL,
  `WebsiteURL` varchar(100) DEFAULT NULL,
  `ImageFileURI` varchar(100) DEFAULT NULL,
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
  `FirstName` varchar(45) DEFAULT NULL,
  `LastName` varchar(45) DEFAULT NULL,
  `UserName` varchar(45) DEFAULT NULL,
  `Password` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'scouting_wiredcats5885_ca'
--

--
-- Dumping routines for database 'scouting_wiredcats5885_ca'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-26 10:20:50
