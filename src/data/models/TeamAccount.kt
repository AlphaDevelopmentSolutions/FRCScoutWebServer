package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class TeamAccount(
    override var id: ByteArray,
    val teamId: ByteArray,
    val name: String,
    val description: String?,
    val username: String,
    val ownerId: ByteArray,
    val avatarUri: String?,
    val primaryColor: String,
    val accentColor: String,
    val createdDate: DateTime,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        name
}