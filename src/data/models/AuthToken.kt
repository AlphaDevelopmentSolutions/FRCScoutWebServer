package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.extensions.toUUID
import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class AuthToken(
    @Transient override var id: ByteArray,
    @SerializedName("user_id") val userId: ByteArray,
    @SerializedName("ip") val ip: Int,
    @SerializedName("expires") val expires: DateTime
) : ByteArrayTable(id) {
    override fun toString() =
        id.toUUID().toString()
}