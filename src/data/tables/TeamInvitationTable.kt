package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import org.jetbrains.exposed.sql.ResultRow

object TeamInvitationTable : ModifyTrackedTable<TeamInvitation>("team_invitations") {
    var userTeamAccountListId = binary("user_team_account_list_id", 16)
    var state = customEnumeration("state", "ENUM('ACCEPTED', 'DECLINED')", { TeamInvitation.Companion.State.valueOf(it as String) }, { it.name }).nullable()
    var createdDate = datetime("created_date")
    var createdById = binary("created_by_id", 16)

    override fun fromResultRow(resultRow: ResultRow) =
        TeamInvitation(
            resultRow[id],
            resultRow[userTeamAccountListId],
            resultRow[state],
            resultRow[createdDate],
            resultRow[createdById],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )
}