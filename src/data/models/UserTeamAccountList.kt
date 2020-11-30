package com.alphadevelopmentsolutions.data.models

import com.alphadevelopmentsolutions.data.tables.YearTable
import org.jetbrains.exposed.sql.ResultRow
import org.joda.time.DateTime

class UserTeamAccountList(
    override var id: ByteArray,
    val userId: ByteArray,
    val teamAccountId: ByteArray,
    val state: State,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
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