package com.alphadevelopmentsolutions.data.tables

object MatchTable : ModifyableTable("matches") {
    var eventId = binary("event_id", 16)
    var key = varchar("key", 45)
    var typeId = binary("type_id", 16)
    var setNumber = integer("set_number")
    var matchNumber = integer("match_number")
    var blueAllianceTeamOneId = binary("blue_alliance_team_one_id", 16)
    var blueAllianceTeamTwoId = binary("blue_alliance_team_two_id", 16)
    var blueAllianceTeamThreeId = binary("blue_alliance_team_three_id", 16)
    var redAllianceTeamOneId = binary("red_alliance_team_one_id", 16)
    var redAllianceTeamTwoId = binary("red_alliance_team_two_id", 16)
    var redAllianceTeamThreeId = binary("red_alliance_team_three_id", 16)
    var blueAllianceScore = integer("blue_alliance_score").nullable()
    var redAllianceScore = integer("red_alliance_score").nullable()
    var time = datetime("time").nullable()
}