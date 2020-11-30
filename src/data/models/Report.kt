package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class Report(
    override var id: ByteArray,
    val recordId: ByteArray?,
    val comment: String,
    val reporterId: ByteArray,
    val reportedDate: DateTime,
    val isResolved: Boolean,
    val isNotified: Boolean,
    val createdDate: DateTime,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        comment
}