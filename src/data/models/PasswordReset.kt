package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class PasswordReset(
    @Transient override var id: ByteArray,
    @SerializedName("user_id") val userId: ByteArray,
    @SerializedName("expires") val expires: DateTime,
    @SerializedName("is_used") val isUsed: Boolean,
    @SerializedName("created_date") val createdDate: DateTime
) : ByteArrayTable(id) {
    override fun toString() =
        "Expires $expires"
}