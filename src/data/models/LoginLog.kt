package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class LoginLog(
    @Transient override var id: ByteArray,
    @SerializedName("username") val username: String?,
    @SerializedName("password") val password: String?,
    @SerializedName("ip") val ip: Int,
    @SerializedName("time") val time: DateTime,
    @SerializedName("user_agent") val userAgent: String,
    @SerializedName("user_id") val userId: ByteArray?
) : ByteArrayTable(id) {
    override fun toString() =
        ip.toString()
}