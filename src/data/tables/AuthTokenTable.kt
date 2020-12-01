package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.AuthToken
import org.jetbrains.exposed.sql.*

object AuthTokenTable : ByteArrayTable<AuthToken>("auth_tokens") {
    var userId = binary("user_id", 16)
    var ip = integer("ip")
    var expires = datetime("expires")

    override fun fromResultRow(resultRow: ResultRow) =
            AuthToken(
                resultRow[id],
                resultRow[userId],
                resultRow[ip],
                resultRow[expires]
            )

    override fun insert(obj: AuthToken) =
        insert {
            it[id] = obj.id
            it[userId] = obj.userId
            it[ip] = obj.ip
            it[expires] = obj.expires
        }

    override fun update(obj: AuthToken, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[userId] = obj.userId
            it[ip] = obj.ip
            it[expires] = obj.expires
        }
}