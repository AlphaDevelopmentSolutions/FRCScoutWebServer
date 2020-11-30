package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class ChecklistItem(
    @Transient override var id: ByteArray,
    @SerializedName("team_account_id") val teamAccountId: ByteArray,
    @SerializedName("year_id") val yearId: ByteArray,
    @SerializedName("title") val title: String,
    @SerializedName("description") val description: String,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        title
}