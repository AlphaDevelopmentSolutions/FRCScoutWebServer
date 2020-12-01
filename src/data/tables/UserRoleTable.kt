package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.UserRole
import org.jetbrains.exposed.sql.*

object UserRoleTable : ModifyableTable<UserRole>("user_roles") {
    var userTeamAccountListId = binary("user_team_account_list_id", 16)
    var roleId = binary("role_id", 16)

    override fun fromResultRow(resultRow: ResultRow) =
        UserRole(
            resultRow[id],
            resultRow[userTeamAccountListId],
            resultRow[roleId],
            resultRow[lastModified]
        )

    override fun insert(obj: UserRole) =
        insert {
            it[id] = obj.id
            it[userTeamAccountListId] = obj.userTeamAccountListId
            it[roleId] = obj.roleId
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: UserRole, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[userTeamAccountListId] = obj.userTeamAccountListId
            it[roleId] = obj.roleId
            it[lastModified] = obj.lastModified
        }
}