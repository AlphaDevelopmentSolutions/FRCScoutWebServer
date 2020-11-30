package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

abstract class ModifyableTable(
    @Transient override var id: ByteArray,
    @SerializedName("last_modified") open val lastModified: DateTime
) : ByteArrayTable(id)