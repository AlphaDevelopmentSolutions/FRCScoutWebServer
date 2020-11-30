package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.EventTeamList
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object EventTeamListTable : ModifyableTable<EventTeamList>("event_team_list") {
    var teamId = binary("team_account", 16)
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

    override fun update(obj: EventTeamList) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[teamId] = obj.teamId
            it[eventId] = obj.eventId
            it[lastModified] = obj.lastModified
        }
}