package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class User(
    override var id: ByteArray,
    val firstName: String,
    val lastName: String,
    val email: String,
    val password: String,
    val description: String,
    val avatarUri: String,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }

    companion object {
        enum class State {

        }
    }
}