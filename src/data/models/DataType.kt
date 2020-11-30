package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class DataType(
    @Transient override var id: ByteArray,
    @SerializedName("name") val name: String,
    @SerializedName("can_max") val canMax: Boolean,
    @SerializedName("can_min") val canMin: Boolean,
    @SerializedName("can_null_zeros") val canNullZeros: Boolean,
    @SerializedName("can_report") val canReport: Boolean,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        name
}