package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.data.tables.YearTable
import com.google.gson.annotations.SerializedName
import org.jetbrains.exposed.sql.ResultRow
import org.joda.time.DateTime

class UserTeamAccountList(
    @Transient override var id: ByteArray,
    @SerializedName("user_id") val userId: ByteArray,
    @SerializedName("team_account_id") val teamAccountId: ByteArray,
    @SerializedName("state") val state: State?,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        state.toString()

    companion object {
        enum class State {
            ENABLED,
            DISABLED
        }
    }
}