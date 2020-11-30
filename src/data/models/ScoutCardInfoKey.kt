package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class ScoutCardInfoKey(
    @Transient override var id: ByteArray,
    @SerializedName("state_id") val stateId: ByteArray,
    @SerializedName("data_type_id") val dataTypeId: ByteArray,
    @SerializedName("name") val name: String,
    @SerializedName("description") val description: String?,
    @SerializedName("order") val order: Int,
    @SerializedName("min") val min: Int?,
    @SerializedName("max") val max: Int?,
    @SerializedName("null_zeros") val nullZeros: Boolean?,
    @SerializedName("include_in_reports") val includeInReports: Boolean?,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        name
}