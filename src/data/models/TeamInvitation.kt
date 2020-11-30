package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class TeamInvitation(
    @Transient override var id: ByteArray,
    val userTeamAccountListId: ByteArray,
    val state: State?,
    val createdDate: DateTime,
    val createdById: ByteArray,
    @Transient override val deletedDate: DateTime?,
    @Transient override val deletedById: ByteArray?,
    @Transient override val lastModified: DateTime,
    @Transient override val modifiedById: ByteArray
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