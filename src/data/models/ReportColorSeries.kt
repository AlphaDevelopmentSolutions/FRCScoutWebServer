package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class ReportColorSeries(
    override var id: ByteArray,
    val reportId: ByteArray,
    val value: Boolean,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}