package com.alphadevelopmentsolutions.data.tables

object TeamAccountTable : ModifyTrackedTable("team_accounts") {
    var teamId = binary("team_id", 16)
    var name = varchar("name", 150)
    var description = varchar("description", 300).nullable()
    var username = varchar("username", 45)
    var ownerId = binary("owner_id", 16)
    var avatarUri = binary("avatar_uri", 16)
    var primaryColor = varchar("primary_color", 7)
    var accentColor = varchar("accent_color", 7)
    var createdDate = datetime("created_date")
}