package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class TeamInvitation(
    override var id: ByteArray,
    val userTeamAccountListId: ByteArray,
    val state: State?,
    val createdDate: DateTime,
    val createdById: ByteArray,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString() =
        state?.name ?: "Empty"

    companion object {
        enum class State {
            ACCEPTED,
            DECLINED
        }
    }
}