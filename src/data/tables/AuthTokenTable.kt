package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.AuthToken
import org.jetbrains.exposed.sql.ResultRow

object AuthTokenTable : ByteArrayTable<AuthToken>("auth_tokens") {
    var userId = binary("user_id", 16)
    var token = varchar("token", 64)
    var ip = integer("ip")
    var expires = datetime("expires")
    override fun fromResultRow(resultRow: ResultRow): AuthToken {
        TODO("Not yet implemented")
    }
}