package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object MatchType : ModifyableTable("match_types") {
    var key = varchar("key", 4)
    var name = varchar("name", 45)
}