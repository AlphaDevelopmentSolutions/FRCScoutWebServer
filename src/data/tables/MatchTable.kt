package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Match
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object MatchTable : ModifyableTable<Match>("matches") {
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

    override fun fromResultRow(resultRow: ResultRow) =
        Match(
            resultRow[id],
            resultRow[eventId],
            resultRow[key],
            resultRow[typeId],
            resultRow[setNumber],
            resultRow[matchNumber],
            resultRow[blueAllianceTeamOneId],
            resultRow[blueAllianceTeamTwoId],
            resultRow[blueAllianceTeamThreeId],
            resultRow[redAllianceTeamOneId],
            resultRow[redAllianceTeamTwoId],
            resultRow[redAllianceTeamThreeId],
            resultRow[blueAllianceScore],
            resultRow[redAllianceScore],
            resultRow[time],
            resultRow[lastModified]
        )

    override fun insert(obj: Match) =
        insert {
            it[id] = obj.id
            it[eventId] = obj.eventId
            it[key] = obj.key
            it[typeId] = obj.typeId
            it[setNumber] = obj.setNumber
            it[matchNumber] = obj.matchNumber
            it[blueAllianceTeamOneId] = obj.blueAllianceTeamOneId
            it[blueAllianceTeamTwoId] = obj.blueAllianceTeamTwoId
            it[blueAllianceTeamThreeId] = obj.blueAllianceTeamThreeId
            it[redAllianceTeamOneId] = obj.redAllianceTeamOneId
            it[redAllianceTeamTwoId] = obj.redAllianceTeamTwoId
            it[redAllianceTeamThreeId] = obj.redAllianceTeamThreeId
            it[blueAllianceScore] = obj.blueAllianceScore
            it[redAllianceScore] = obj.redAllianceScore
            it[time] = obj.time
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: Match) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[eventId] = obj.eventId
            it[key] = obj.key
            it[typeId] = obj.typeId
            it[setNumber] = obj.setNumber
            it[matchNumber] = obj.matchNumber
            it[blueAllianceTeamOneId] = obj.blueAllianceTeamOneId
            it[blueAllianceTeamTwoId] = obj.blueAllianceTeamTwoId
            it[blueAllianceTeamThreeId] = obj.blueAllianceTeamThreeId
            it[redAllianceTeamOneId] = obj.redAllianceTeamOneId
            it[redAllianceTeamTwoId] = obj.redAllianceTeamTwoId
            it[redAllianceTeamThreeId] = obj.redAllianceTeamThreeId
            it[blueAllianceScore] = obj.blueAllianceScore
            it[redAllianceScore] = obj.redAllianceScore
            it[time] = obj.time
            it[lastModified] = obj.lastModified
        }
}