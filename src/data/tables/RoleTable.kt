package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Role
import com.google.gson.annotations.SerializedName
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.Table

object RoleTable : ModifyTrackedTable<Role>("roles") {
    var teamAccountId = binary("team_account_id", 16)
    var name = varchar("name", 20)
    var description = varchar("description", 200)
    var canManageTeam = bool("can_manage_team")
    var canManageUsers = bool("can_manage_users")
    var canMatchScout = bool("can_match_scout")
    var canPitScout = bool("can_pit_scout")
    var canCaptureMedia = bool("can_capture_media")
    var canManageReports = bool("can_manage_reports")

    override fun fromResultRow(resultRow: ResultRow) =
        Role(
            resultRow[id],
            resultRow[teamAccountId],
            resultRow[name],
            resultRow[description],
            resultRow[canManageTeam],
            resultRow[canManageUsers],
            resultRow[canMatchScout],
            resultRow[canPitScout],
            resultRow[canCaptureMedia],
            resultRow[canManageReports],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )
}