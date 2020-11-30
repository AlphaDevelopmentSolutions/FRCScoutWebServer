package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class ChecklistItem(
    override var id: ByteArray,
    val teamAccountId: ByteArray,
    val yearId: ByteArray,
    val title: String,
    val description: String,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        title
}