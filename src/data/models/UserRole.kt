package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class UserRole(
    override var id: ByteArray,
    val userTeamAccountListId: ByteArray,
    val roleId: ByteArray,
    override val lastModified: DateTime
) : ModifyableTable(id, lastModified) {
    override fun toString() =
        "UserRole Object"
}