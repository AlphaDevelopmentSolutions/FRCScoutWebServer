package com.alphadevelopmentsolutions.scraper.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Match(
    @SerializedName("key") val key: String,
    @SerializedName("comp_level") val compLevel: CompLevel,
    @SerializedName("set_number") val setNumber: Int,
    @SerializedName("match_number") val matchNumber: Int,
    @SerializedName("alliances") val alliances: Alliances,
    @SerializedName("time") val time: Long?
) {
    enum class CompLevel {
        qm,
        ef,
        qf,
        sf,
        f;
    }
}