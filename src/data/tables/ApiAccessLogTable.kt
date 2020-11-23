package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object ApiAccessLogTable : ByteArrayTable("api_access_logs") {
    var endpoint = binary("endpoint", 16)
    var ip = integer("ip")
    var userAgent = varchar("user_agent", 100)
    var time = datetime("time")
    var userTeamAccountListId = binary("user_team_account_list_id", 16).nullable()
    var authTokenId = binary("auth_token_id", 16).nullable()
}