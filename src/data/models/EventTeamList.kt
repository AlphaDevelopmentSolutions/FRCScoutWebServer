package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class EventTeamList(
    override var id: ByteArray,
    val teamId: ByteArray,
    val eventId: ByteArray,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        "Event Team List Object"
}