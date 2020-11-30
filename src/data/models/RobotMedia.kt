package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class RobotMedia(
    @Transient override var id: ByteArray,
    @SerializedName("team_account_id") val teamAccountId: ByteArray,
    @SerializedName("event_id") val eventId: ByteArray,
    @SerializedName("team_id") val teamId: ByteArray,
    @SerializedName("created_by_id") val createdById: ByteArray,
    @SerializedName("uri") val uri: String,
    @SerializedName("is_public") val isPublic: Boolean,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        uri
}