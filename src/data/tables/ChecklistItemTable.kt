package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object ChecklistItemTable : ModifyTrackedTable("checklist_items") {
    var teamAccountId = binary("team_account_id", 16)
    var yearId = binary("year_id", 16)
    var title = varchar("title", 100)
    var description = varchar("description", 300)
}