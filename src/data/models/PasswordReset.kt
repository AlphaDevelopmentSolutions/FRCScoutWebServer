package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class PasswordReset(
    override var id: ByteArray,
    val userId: ByteArray,
    val expires: DateTime,
    val isUsed: Boolean,
    val createdDate: DateTime
) : ByteArrayTable(id) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}