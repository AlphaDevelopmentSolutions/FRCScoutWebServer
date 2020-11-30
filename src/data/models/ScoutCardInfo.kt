package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class ScoutCardInfo(
    @Transient override var id: ByteArray,
    @SerializedName("match_id") val matchId: ByteArray,
    @SerializedName("team_id") val teamId: ByteArray,
    @SerializedName("key_id") val keyId: ByteArray,
    @SerializedName("value") val value: String,
    @SerializedName("completed_by_id") val completedById: ByteArray,
    @SerializedName("is_public") val isPublic: Boolean,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        value
}