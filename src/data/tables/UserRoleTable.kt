package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.UserRole
import org.jetbrains.exposed.sql.ResultRow

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
}