package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamAccount
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

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

    override fun insert(obj: TeamAccount) =
        insert {
            it[id] = obj.id
            it[teamId] = obj.teamId
            it[name] = obj.name
            it[description] = obj.description
            it[username] = obj.username
            it[ownerId] = obj.ownerId
            it[avatarUri] = obj.avatarUri
            it[primaryColor] = obj.primaryColor
            it[accentColor] = obj.accentColor
            it[createdDate] = obj.createdDate
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: TeamAccount) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[teamId] = obj.teamId
            it[name] = obj.name
            it[description] = obj.description
            it[username] = obj.username
            it[ownerId] = obj.ownerId
            it[avatarUri] = obj.avatarUri
            it[primaryColor] = obj.primaryColor
            it[accentColor] = obj.accentColor
            it[createdDate] = obj.createdDate
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}