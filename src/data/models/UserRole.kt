package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class UserRole(
    override var id: ByteArray,
    val userTeamAccountListId: ByteArray,
    val roleId: ByteArray,
    val avatarUri: String,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }

    companion object {
        enum class State {

        }
    }
}