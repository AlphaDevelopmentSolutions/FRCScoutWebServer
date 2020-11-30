package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class ReportFilterValue(
    @Transient override var id: ByteArray,
    @SerializedName("report_filter_id") val reportFilterId: ByteArray,
    @SerializedName("value") val value: String,
    @Transient override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        value
}