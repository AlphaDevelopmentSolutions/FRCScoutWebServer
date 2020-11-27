package com.alphadevelopmentsolutions.data.tables

object RobotInfoKeyStateTable : ModifyTrackedTable("robot_info_key_states") {
    var teamAccountId = binary("team_account_id", 16)
    var yearId = binary("year_id", 16)
    var name = varchar("name", 16)
    var description = varchar("description", 200).nullable()
}