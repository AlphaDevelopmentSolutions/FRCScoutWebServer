package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class AuthToken(
    override var id: ByteArray,
    val userId: ByteArray,
    val token: String,
    val ip: Int,
    val expires: DateTime
) : ByteArrayTable(id) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}