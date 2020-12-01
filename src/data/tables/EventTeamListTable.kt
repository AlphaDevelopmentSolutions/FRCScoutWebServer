package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.EventTeamList
import org.jetbrains.exposed.sql.*

object EventTeamListTable : ModifyableTable<EventTeamList>("event_team_list") {
    var teamId = binary("team_id", 16)
    var eventId = binary("event_id", 16)

    override fun fromResultRow(resultRow: ResultRow) =
        EventTeamList(
            resultRow[id],
            resultRow[teamId],
            resultRow[eventId],
            resultRow[lastModified]
        )

    override fun insert(obj: EventTeamList) =
        insert {
            it[id] = obj.id
            it[teamId] = obj.teamId
            it[eventId] = obj.eventId
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: EventTeamList, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[teamId] = obj.teamId
            it[eventId] = obj.eventId
            it[lastModified] = obj.lastModified
        }
}