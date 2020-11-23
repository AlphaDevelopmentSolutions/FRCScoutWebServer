package com.alphadevelopmentsolutions.data.tables

import com.google.gson.annotations.SerializedName
import org.jetbrains.exposed.sql.Table

object RoleTable : ModifyTrackedTable("roles") {
    var teamAccountId = binary("team_account_id", 16)
    var name = varchar("name", 20)
    var description = varchar("description", 200)
    var canManageTeam = bool("can_manage_team")
    var canManageUsers = bool("can_manage_users")
    var canMatchScout = bool("can_match_scout")
    var canPitScout = bool("can_pit_scout")
    var canCaptureMedia = bool("can_capture_media")
    var canManageReports = bool("can_manage_reports")
}