package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class ReportFilterValue(
    override var id: ByteArray,
    val reportFilterId: ByteArray,
    val value: String,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        value
}