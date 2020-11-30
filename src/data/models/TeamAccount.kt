package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class TeamAccount(
    @Transient override var id: ByteArray,
    @SerializedName("team_id") val teamId: ByteArray,
    @SerializedName("name") val name: String,
    @SerializedName("description") val description: String?,
    @SerializedName("username") val username: String,
    @SerializedName("owner_id") val ownerId: ByteArray,
    @SerializedName("avatar_uri") val avatarUri: String?,
    @SerializedName("primary_color") val primaryColor: String,
    @SerializedName("accent_color") val accentColor: String,
    @SerializedName("created_date") val createdDate: DateTime,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        name
}