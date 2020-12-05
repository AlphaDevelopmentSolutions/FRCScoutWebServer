package com.alphadevelopmentsolutions.scrapers.models

import com.alphadevelopmentsolutions.scrapers.models.Alliance
import com.google.gson.annotations.SerializedName

class Alliances(
    @SerializedName("red") val redAlliance: Alliance,
    @SerializedName("blue") val blueAlliance: Alliance
)