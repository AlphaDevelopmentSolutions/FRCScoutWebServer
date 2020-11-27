package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class Blacklist(
    override var id: ByteArray,
    val ip: Int,
    val added: DateTime,
    val penaltyId: ByteArray
) : ByteArrayTable(id) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}