package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class ApiAccessLog(
    @Transient override var id: ByteArray,
    @SerializedName("endpoint") val endpoint: String,
    @SerializedName("ip") val ip: Int,
    @SerializedName("user_agent") val userAgent: String,
    @SerializedName("time") val time: DateTime,
    @SerializedName("user_team_account_list_id") val userTeamAccountListId: ByteArray?,
    @SerializedName("auth_token_id") val authTokenId: ByteArray?
) : ByteArrayTable(id) {
    override fun toString() =
        endpoint
}