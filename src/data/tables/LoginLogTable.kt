package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.LoginLog
import org.jetbrains.exposed.sql.*

object LoginLogTable : ByteArrayTable<LoginLog>("login_logs") {
    var username = varchar("username", 20).nullable()
    var password = varchar("password", 100).nullable()
    var ip = integer("ip")
    var time = datetime("time")
    var userAgent = varchar("user_agent", 100)
    var userId = binary("user_id", 16).nullable()

    override fun fromResultRow(resultRow: ResultRow) =
        LoginLog(
            resultRow[id],
            resultRow[username],
            resultRow[password],
            resultRow[ip],
            resultRow[time],
            resultRow[userAgent],
            resultRow[userId]
        )

    override fun insert(obj: LoginLog) =
        insert {
            it[id] = obj.id
            it[username] = obj.username
            it[password] = obj.password
            it[ip] = obj.ip
            it[time] = obj.time
            it[userAgent] = obj.userAgent
            it[userId] = obj.userId
        }

    override fun update(obj: LoginLog, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[username] = obj.username
            it[password] = obj.password
            it[ip] = obj.ip
            it[time] = obj.time
            it[userAgent] = obj.userAgent
            it[userId] = obj.userId
        }
}