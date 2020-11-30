package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

abstract class ModifyTrackedTable(
    @Transient override var id: ByteArray,
    @SerializedName("deleted_date") open val deletedDate: DateTime?,
    @SerializedName("deleted_by_id") open val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @SerializedName("modified_by_id") open val modifiedById: ByteArray
) : ModifyableTable(id, lastModified)