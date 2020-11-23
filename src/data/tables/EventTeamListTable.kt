package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object EventTeamListTable : ModifyableTable("event_team_list") {
    var teamId = binary("team_account", 16)
    var eventId = binary("event_id", 16)
}