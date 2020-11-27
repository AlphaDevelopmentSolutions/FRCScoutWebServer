package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.User
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName
import org.jetbrains.exposed.sql.ResultRow

object UserTable : ModifyTrackedTable<User>("users") {
    var firstName = varchar("first_name", 30)
    var lastName = varchar("first_name", 30)
    var email = varchar("first_name", 100)
    var username = varchar("first_name", 20)
    var password = varchar("first_name", 100)
    var description = varchar("first_name", 300).nullable()
    var avatarUri = varchar("first_name", 100).nullable()
    override fun fromResultRow(resultRow: ResultRow): User {
        TODO("Not yet implemented")
    }
}