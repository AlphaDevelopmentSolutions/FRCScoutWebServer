package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class Role(
    @Transient override var id: ByteArray,
    @SerializedName("team_account_id") val teamAccountId: ByteArray,
    @SerializedName("name") val name: String,
    @SerializedName("description") val description: String,
    @SerializedName("can_manage_team") val canManageTeam: Boolean,
    @SerializedName("can_manage_users") val canManageUsers: Boolean,
    @SerializedName("can_match_scout") val canMatchScout: Boolean,
    @SerializedName("can_pit_scout") val canPitScout: Boolean,
    @SerializedName("can_capture_media") val canCaptureMedia: Boolean,
    @SerializedName("can_manage_reports") val canManageReports: Boolean,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        name
}