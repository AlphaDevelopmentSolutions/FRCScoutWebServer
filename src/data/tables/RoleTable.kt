package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Role
import com.google.gson.annotations.SerializedName
import org.jetbrains.exposed.sql.*

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

    override fun insert(obj: Role) =
        insert {
            it[id] = obj.id
            it[teamAccountId] = obj.teamAccountId
            it[name] = obj.name
            it[description] = obj.description
            it[canManageTeam] = obj.canManageTeam
            it[canManageUsers] = obj.canManageUsers
            it[canMatchScout] = obj.canMatchScout
            it[canPitScout] = obj.canPitScout
            it[canCaptureMedia] = obj.canCaptureMedia
            it[canManageReports] = obj.canManageReports
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: Role, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[teamAccountId] = obj.teamAccountId
            it[name] = obj.name
            it[description] = obj.description
            it[canManageTeam] = obj.canManageTeam
            it[canManageUsers] = obj.canManageUsers
            it[canMatchScout] = obj.canMatchScout
            it[canPitScout] = obj.canPitScout
            it[canCaptureMedia] = obj.canCaptureMedia
            it[canManageReports] = obj.canManageReports
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}