DROP TABLE IF EXISTS `matches`;
CREATE TABLE `matches` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Date` datetime DEFAULT NULL,
  `EventId` varchar(45) DEFAULT NULL,
  `Key` varchar(45) DEFAULT NULL,
  `MatchType` varchar(5) DEFAULT NULL,
  `SetNumber` int(11) DEFAULT NULL,
  `MatchNumber` int(11) DEFAULT NULL,
  `BlueAllianceTeamOneId` int(11) DEFAULT NULL,
  `BlueAllianceTeamTwoId` int(11) DEFAULT NULL,
  `BlueAllianceTeamThreeId` int(11) DEFAULT NULL,
  `RedAllianceTeamOneId` int(11) DEFAULT NULL,
  `RedAllianceTeamTwoId` int(11) DEFAULT NULL,
  `RedAllianceTeamThreeId` int(11) DEFAULT NULL,
  `BlueAllianceScore` int(11) DEFAULT NULL,
  `RedAllianceScore` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3069 DEFAULT CHARSET=utf8mb4;

UPDATE scout_cards, matches
SET scout_cards.MatchId = matches.key
WHERE scout_cards.matchid = matches.MatchNumber
      AND scout_cards.EventId = matches.eventid
      AND matches.matchtype = 'qm';

ALTER TABLE scout_cards
  DROP COLUMN `RedAllianceFinalScore`,
  DROP COLUMN `BlueAllianceFinalScore`;