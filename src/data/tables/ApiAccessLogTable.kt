package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ApiAccessLog
import org.jetbrains.exposed.sql.ResultRow

object ApiAccessLogTable : ByteArrayTable<ApiAccessLog>("api_access_logs") {
    var endpoint = varchar("endpoint", 45)
    var ip = integer("ip")
    var userAgent = varchar("user_agent", 100)
    var time = datetime("time")
    var userTeamAccountListId = binary("user_team_account_list_id", 16).nullable()
    var authTokenId = binary("auth_token_id", 16).nullable()

    override fun fromResultRow(resultRow: ResultRow) =
        ApiAccessLog(
            resultRow[id],
            resultRow[endpoint],
            resultRow[ip],
            resultRow[userAgent],
            resultRow[time],
            resultRow[userTeamAccountListId],
            resultRow[authTokenId]
        )
}