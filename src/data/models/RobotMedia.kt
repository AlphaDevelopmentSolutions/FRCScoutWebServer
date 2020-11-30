package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class RobotMedia(
    override var id: ByteArray,
    val teamAccountId: ByteArray,
    val eventId: ByteArray,
    val teamId: ByteArray,
    val createdById: ByteArray,
    val uri: String,
    val isPublic: Boolean,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        uri
}