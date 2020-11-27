package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class Penalty(
    override var id: ByteArray,
    val failedLoginAttemptCount: Int,
    val withinDuration: Int,
    val penaltyDuration: Int
) : ByteArrayTable(id) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}