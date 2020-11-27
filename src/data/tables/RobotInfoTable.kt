package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.RobotInfo
import org.jetbrains.exposed.sql.ResultRow

object RobotInfoTable : ModifyTrackedTable<RobotInfo>("robot_info") {
    var eventId = binary("event_id", 16)
    var teamId = binary("team_id", 16)
    var keyId = binary("key_id", 16)
    var value = varchar("value", 200)
    var completedById = varchar("completed_by_id", 16)
    var isPublic = bool("is_public")
    override fun fromResultRow(resultRow: ResultRow): RobotInfo {
        TODO("Not yet implemented")
    }
}