package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object TeamInvitationTable : ModifyTrackedTable("team_invitations") {
    var userTeamAccountListId = binary("user_team_account_list_id", 16)
    var state = customEnumeration("state", "ENUM('ACCEPTED', 'DECLINED')", { value -> TeamInvitation.State.valueOf(value as String)}, { it.name }).nullable()
    var createdDate = datetime("created_date")
    var createdById = binary("created_by_id", 16)
}