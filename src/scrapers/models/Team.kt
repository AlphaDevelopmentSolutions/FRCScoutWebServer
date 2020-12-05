package com.alphadevelopmentsolutions.scrapers.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Team(
    @SerializedName("team_number") val number: Int,
    @SerializedName("nickname") val name: String,
    @SerializedName("city") val city: String?,
    @SerializedName("state_prov") val stateProvince: String?,
    @SerializedName("country") val country: String?,
    @SerializedName("rookie_year") val rookieYear: Int?,
    @SerializedName("website") val websiteUrl: String?,
    @SerializedName("key") val key: String
)