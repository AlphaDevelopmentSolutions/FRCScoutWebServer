package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import org.jetbrains.exposed.sql.ResultRow

object TeamInvitationTable : ModifyTrackedTable<TeamInvitation>("team_invitations") {
    var userTeamAccountListId = binary("user_team_account_list_id", 16)
    var state = customEnumeration("state", "ENUM('ACCEPTED', 'DECLINED')", { value -> TeamInvitation.Companion.State.valueOf(value as String)}, { it.name }).nullable()
    var createdDate = datetime("created_date")
    var createdById = binary("created_by_id", 16)
    override fun fromResultRow(resultRow: ResultRow): TeamInvitation {
        TODO("Not yet implemented")
    }
}