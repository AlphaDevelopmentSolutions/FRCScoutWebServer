package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class MatchType(
    @Transient override var id: ByteArray,
    @SerializedName("key") val key: String,
    @SerializedName("name") val name: String,
    @Transient override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        name
}