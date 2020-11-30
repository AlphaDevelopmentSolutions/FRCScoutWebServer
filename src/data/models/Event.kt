package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class Event(
    override var id: ByteArray,
    val yearId: ByteArray,
    val code: String,
    val key: String,
    val venue: String,
    val name: String,
    val address: String,
    val city: String?,
    val stateProvince: String?,
    val country: String?,
    val startTime: DateTime?,
    val endTime: DateTime?,
    val websiteUrl: String?,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        name
}