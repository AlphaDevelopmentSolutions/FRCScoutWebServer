package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.AuthToken
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

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
            it[userId] = obj.userId
            it[id] = obj.id
            it[ip] = obj.ip
            it[expires] = obj.expires
        }

    override fun update(obj: AuthToken) =
        update({ id eq obj.id }) {
            it[userId] = obj.userId
            it[id] = obj.id
            it[ip] = obj.ip
            it[expires] = obj.expires
        }
}