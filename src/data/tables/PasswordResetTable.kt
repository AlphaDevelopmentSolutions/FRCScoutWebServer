package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.PasswordReset
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object PasswordResetTable : ByteArrayTable<PasswordReset>("password_resets") {
    var userId = binary("user_id", 16)
    var expires = datetime("expires")
    var isUsed = bool("is_used")
    var createdDate = datetime("created_date")

    override fun fromResultRow(resultRow: ResultRow) =
        PasswordReset(
            resultRow[id],
            resultRow[userId],
            resultRow[expires],
            resultRow[isUsed],
            resultRow[createdDate]
        )

    override fun insert(obj: PasswordReset) =
        insert {
            it[id] = obj.id
            it[userId] = obj.userId
            it[expires] = obj.expires
            it[isUsed] = obj.isUsed
            it[createdDate] = obj.createdDate
        }

    override fun update(obj: PasswordReset) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[userId] = obj.userId
            it[expires] = obj.expires
            it[isUsed] = obj.isUsed
            it[createdDate] = obj.createdDate
        }
}