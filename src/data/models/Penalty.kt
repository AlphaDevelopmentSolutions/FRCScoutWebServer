package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Penalty(
    @Transient override var id: ByteArray,
    @SerializedName("failed_login_attempt_count") val failedLoginAttemptCount: Int,
    @SerializedName("within_duration") val withinDuration: Int,
    @SerializedName("penalty_duration") val penaltyDuration: Int
) : ByteArrayTable(id) {
    override fun toString() =
        "Duration $penaltyDuration"
}