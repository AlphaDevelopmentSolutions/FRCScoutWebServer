package com.alphadevelopmentsolutions.data.tables

object PasswordResetTable : ByteArrayTable("password_resets") {
    var userId = binary("user_id", 16)
    var expires = datetime("expires")
    var isUsed = bool("is_used")
    var createdDate = datetime("created_date")
}