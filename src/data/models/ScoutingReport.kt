package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class ScoutingReport(
    override var id: ByteArray,
    val createdById: ByteArray,
    val teamAccountId: ByteArray,
    val name: String,
    val description: String?,
    val xAxisDataType: String?,
    val xAxisDataUnit: String?,
    val chartType: String?,
    val isPublic: Boolean,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}