package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class RobotInfoKey(
    override var id: ByteArray,
    val stateId: ByteArray,
    val dataTypeId: ByteArray,
    val name: String,
    val description: String?,
    val order: Int?,
    val min: Int?,
    val max: Int?,
    val nullZeros: Boolean?,
    val includeInReports: Boolean?,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        name
}