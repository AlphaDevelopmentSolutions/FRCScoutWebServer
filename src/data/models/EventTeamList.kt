package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class EventTeamList(
    @Transient override var id: ByteArray,
    @SerializedName("team_id") val teamId: ByteArray,
    @SerializedName("event_id") val eventId: ByteArray,
    @Transient override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        "Event Team List Object"
}