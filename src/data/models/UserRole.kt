package com.alphadevelopmentsolutions.data.models

import com.google.gson.annotations.SerializedName
import org.joda.time.DateTime

class UserRole(
    @Transient override var id: ByteArray,
    @SerializedName("user_team_account_list_id") val userTeamAccountListId: ByteArray,
    @SerializedName("role_id") val roleId: ByteArray,
    @Transient override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        "UserRole Object"
}