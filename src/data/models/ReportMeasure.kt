package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class ReportMeasure(
    override var id: ByteArray,
    val reportId: ByteArray,
    val value: String,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        value
}