package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class RobotInfoKeyState(
    override var id: ByteArray,
    val teamAccountId: ByteArray,
    val yearId: ByteArray,
    val name: String,
    val description: String?,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}