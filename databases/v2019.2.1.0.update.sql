UPDATE scout_cards, matches
SET scout_cards.MatchId = matches.key
WHERE scout_cards.matchid = matches.MatchNumber
AND scout_cards.EventId = matches.eventid
AND matches.matchtype = 'qm';

ALTER TABLE scout_cards
DROP COLUMN `RedAllianceFinalScore`,
DROP COLUMN `BlueAllianceFinalScore`;
