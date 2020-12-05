package com.alphadevelopmentsolutions.scrapers.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Event(
    @SerializedName("year") val yearId: Int,
    @SerializedName("event_code") val code: String,
    @SerializedName("key") val key: String,
    @SerializedName("location_name") val venue: String?,
    @SerializedName("name") val name: String,
    @SerializedName("address") val address: String?,
    @SerializedName("city") val city: String?,
    @SerializedName("state_prov") val stateProvince: String?,
    @SerializedName("country") val country: String?,
    @SerializedName("start_date") val startTime: DateTime?,
    @SerializedName("end_date") val endTime: DateTime?,
    @SerializedName("website") val websiteUrl: String?
)