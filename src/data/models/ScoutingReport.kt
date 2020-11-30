package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class ScoutingReport(
    @Transient override var id: ByteArray,
    @SerializedName("created_by_id") val createdById: ByteArray,
    @SerializedName("team_account_id") val teamAccountId: ByteArray,
    @SerializedName("name") val name: String,
    @SerializedName("description") val description: String?,
    @SerializedName("x_axis_data_type") val xAxisDataType: String?,
    @SerializedName("x_axis_data_unit") val xAxisDataUnit: String?,
    @SerializedName("chart_type") val chartType: String?,
    @SerializedName("is_public") val isPublic: Boolean,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        name
}