package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class ApiAccessLog(
    override var id: ByteArray,
    val endpoint: String,
    val ip: Int,
    val userAgent: String,
    val time: DateTime,
    val userTeamAccountListId: ByteArray?,
    val authTokenId: ByteArray?
) : ByteArrayTable(id) {
    override fun toString() =
        endpoint
}