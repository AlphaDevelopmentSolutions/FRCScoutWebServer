package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object UserTable : ModifyTrackedTable("users") {
    var firstName = varchar("first_name", 30)
    var lastName = varchar("first_name", 30)
    var email = varchar("first_name", 100)
    var username = varchar("first_name", 20)
    var password = varchar("first_name", 100)
    var description = varchar("first_name", 300).nullable()
    var avatarUri = varchar("first_name", 100).nullable()
}