package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Report(
    @Transient override var id: ByteArray,
    @SerializedName("record_id") val recordId: ByteArray?,
    @SerializedName("comment") val comment: String,
    @SerializedName("reporter_id") val reporterId: ByteArray,
    @SerializedName("reported_date") val reportedDate: DateTime,
    @SerializedName("is_resolved") val isResolved: Boolean,
    @SerializedName("is_notified") val isNotified: Boolean,
    @SerializedName("created_date") val createdDate: DateTime,
    @Transient override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        comment
}