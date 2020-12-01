package com.alphadevelopmentsolutions.scraper.models

import com.google.gson.annotations.SerializedName

class Alliances(
    @SerializedName("red") val redAlliance: Alliance,
    @SerializedName("blue") val blueAlliance: Alliance
)