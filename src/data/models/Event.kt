package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Event(
    @Transient override var id: ByteArray,
    @SerializedName("year_id") val yearId: ByteArray,
    @SerializedName("code") val code: String,
    @SerializedName("key") val key: String,
    @SerializedName("venue") val venue: String?,
    @SerializedName("name") val name: String,
    @SerializedName("address") val address: String?,
    @SerializedName("city") val city: String?,
    @SerializedName("state_province") val stateProvince: String?,
    @SerializedName("country") val country: String?,
    @SerializedName("start_time") val startTime: DateTime?,
    @SerializedName("end_time") val endTime: DateTime?,
    @SerializedName("website_url") val websiteUrl: String?,
    @Transient override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        name
}