package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.PasswordReset
import org.jetbrains.exposed.sql.ResultRow

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
}