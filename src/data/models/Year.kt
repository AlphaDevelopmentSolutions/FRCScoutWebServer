package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.data.tables.YearTable
import com.google.gson.annotations.SerializedName
import org.jetbrains.exposed.sql.ResultRow
import org.joda.time.DateTime

class Year(
    @Transient override var id: ByteArray,
    @SerializedName("number") val number: Int,
    @SerializedName("name") val name: String,
    @SerializedName("start_date") val startDate: DateTime?,
    @SerializedName("end_date") val endDate: DateTime?,
    @SerializedName("image_uri") val imageUri: String?,
    @Transient override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        name
}