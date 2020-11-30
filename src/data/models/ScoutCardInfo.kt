package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class ScoutCardInfo(
    override var id: ByteArray,
    val matchId: ByteArray,
    val teamId: ByteArray,
    val keyId: ByteArray,
    val value: String,
    val completedById: ByteArray,
    val isPublic: Boolean,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        value
}