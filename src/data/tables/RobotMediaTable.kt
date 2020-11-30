package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.RobotMedia
import org.jetbrains.exposed.sql.ResultRow

object RobotMediaTable : ModifyTrackedTable<RobotMedia>("robot_media") {
    var teamAccountId = binary("team_account_id", 16)
    var eventId = binary("event_id", 16)
    var teamId = binary("team_id", 16)
    var createdById = binary("created_by_id", 16)
    var uri = varchar("uri", 100)
    var isPublic = bool("is_public")

    override fun fromResultRow(resultRow: ResultRow) =
        RobotMedia(
            resultRow[id],
            resultRow[teamAccountId],
            resultRow[eventId],
            resultRow[teamId],
            resultRow[createdById],
            resultRow[uri],
            resultRow[isPublic],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )
}