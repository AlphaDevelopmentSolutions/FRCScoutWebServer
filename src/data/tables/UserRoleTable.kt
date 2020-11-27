package com.alphadevelopmentsolutions.data.tables

object UserRoleTable : ModifyableTable("user_roles") {
    var userTeamAccountListId = binary("user_team_account_list_id", 16)
    var roleId = binary("role_id", 16)
}