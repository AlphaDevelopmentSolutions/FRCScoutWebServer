package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object AuthTokenTable : ByteArrayTable("auth_tokens") {
    var userId = binary("user_id", 16)
    var token = varchar("token", 64)
    var ip = integer("ip")
    var expires = datetime("expires")
}