package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ApiAccessLog
import com.alphadevelopmentsolutions.data.models.User
import org.jetbrains.exposed.sql.*
import org.jetbrains.exposed.sql.SqlExpressionBuilder.eq
import org.jetbrains.exposed.sql.statements.InsertStatement

object ApiAccessLogTable : ByteArrayTable<ApiAccessLog>("api_access_logs") {
    var endpoint = varchar("endpoint", 45)
    var ip = integer("ip")
    var userAgent = varchar("user_agent", 200)
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

    override fun insert(obj: ApiAccessLog) =
        insert {
            it[id] = obj.id
            it[endpoint] = obj.endpoint
            it[ip] = obj.ip
            it[userAgent] = obj.userAgent
            it[time] = obj.time
            it[userTeamAccountListId] = obj.userTeamAccountListId
            it[authTokenId] = obj.authTokenId
        }

    override fun update(obj: ApiAccessLog, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { TeamTable.id eq obj.id }) {
            it[endpoint] = obj.endpoint
            it[ip] = obj.ip
            it[userAgent] = obj.userAgent
            it[time] = obj.time
            it[userTeamAccountListId] = obj.userTeamAccountListId
            it[authTokenId] = obj.authTokenId
        }
}