package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.extensions.toIP
import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Blacklist(
    @Transient override var id: ByteArray,
    @SerializedName("ip") val ip: Int,
    @SerializedName("added") val added: DateTime,
    @SerializedName("penalty_id") val penaltyId: ByteArray
) : ByteArrayTable(id) {
    override fun toString() =
        ip.toIP()
}