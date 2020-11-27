package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.RobotInfoKeyState
import org.jetbrains.exposed.sql.ResultRow

object RobotInfoKeyStateTable : ModifyTrackedTable<RobotInfoKeyState>("robot_info_key_states") {
    var teamAccountId = binary("team_account_id", 16)
    var yearId = binary("year_id", 16)
    var name = varchar("name", 16)
    var description = varchar("description", 200).nullable()
    override fun fromResultRow(resultRow: ResultRow): RobotInfoKeyState {
        TODO("Not yet implemented")
    }
}