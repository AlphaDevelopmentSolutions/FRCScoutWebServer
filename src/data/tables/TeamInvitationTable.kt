package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object TeamInvitationTable : ModifyTrackedTable<TeamInvitation>("team_invitations") {
    var userTeamAccountListId = binary("user_team_account_list_id", 16)
    var state = customEnumeration("state", "ENUM('ACCEPTED', 'DECLINED')", { TeamInvitation.Companion.State.valueOf(it as String) }, { it.name }).nullable()
    var createdDate = datetime("created_date")
    var createdById = binary("created_by_id", 16)

    override fun fromResultRow(resultRow: ResultRow) =
        TeamInvitation(
            resultRow[id],
            resultRow[userTeamAccountListId],
            resultRow[state],
            resultRow[createdDate],
            resultRow[createdById],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )

    override fun insert(obj: TeamInvitation) =
        insert {
            it[id] = obj.id
            it[userTeamAccountListId] = obj.userTeamAccountListId
            it[state] = obj.state
            it[createdDate] = obj.createdDate
            it[createdById] = obj.createdById
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: TeamInvitation) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[userTeamAccountListId] = obj.userTeamAccountListId
            it[state] = obj.state
            it[createdDate] = obj.createdDate
            it[createdById] = obj.createdById
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}