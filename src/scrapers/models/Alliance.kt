package com.alphadevelopmentsolutions.scrapers.models

import com.google.gson.annotations.SerializedName

class Alliance(
    @SerializedName("score") val score: Int?,
    @SerializedName("team_keys") val teamKeys: List<String>
)