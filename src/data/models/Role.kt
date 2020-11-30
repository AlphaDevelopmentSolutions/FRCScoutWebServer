package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class Role(
    override var id: ByteArray,
    val teamAccountId: ByteArray,
    val name: String,
    val description: String,
    val canManageTeam: Boolean,
    val canManageUsers: Boolean,
    val canMatchScout: Boolean,
    val canPitScout: Boolean,
    val canCaptureMedia: Boolean,
    val canManageReports: Boolean,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        name
}