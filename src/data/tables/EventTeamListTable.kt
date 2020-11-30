package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.EventTeamList
import org.jetbrains.exposed.sql.ResultRow

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
}