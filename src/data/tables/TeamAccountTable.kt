package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamAccount
import org.jetbrains.exposed.sql.ResultRow

object TeamAccountTable : ModifyTrackedTable<TeamAccount>("team_accounts") {
    var teamId = binary("team_id", 16)
    var name = varchar("name", 150)
    var description = varchar("description", 300).nullable()
    var username = varchar("username", 45)
    var ownerId = binary("owner_id", 16)
    var avatarUri = varchar("avatar_uri", 100).nullable()
    var primaryColor = varchar("primary_color", 7)
    var accentColor = varchar("accent_color", 7)
    var createdDate = datetime("created_date")

    override fun fromResultRow(resultRow: ResultRow) =
        TeamAccount(
            resultRow[id],
            resultRow[teamId],
            resultRow[name],
            resultRow[description],
            resultRow[username],
            resultRow[ownerId],
            resultRow[avatarUri],
            resultRow[primaryColor],
            resultRow[accentColor],
            resultRow[createdDate],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )
}