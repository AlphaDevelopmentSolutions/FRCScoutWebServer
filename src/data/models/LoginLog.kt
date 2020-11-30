package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class LoginLog(
    override var id: ByteArray,
    val username: String?,
    val password: String?,
    val ip: Int,
    val time: DateTime,
    val userAgent: String,
    val userId: ByteArray?
) : ByteArrayTable(id) {
    override fun toString() =
        ip.toString()
}