package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class DataType(
    override var id: ByteArray,
    val name: String,
    val canMax: Boolean,
    val canMin: Boolean,
    val canNullZeros: Boolean,
    val canReport: Boolean,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }
}