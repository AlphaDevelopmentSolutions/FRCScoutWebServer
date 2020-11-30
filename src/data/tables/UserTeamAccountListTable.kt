package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object UserTeamAccountListTable : ModifyTrackedTable<UserTeamAccountList>("user_team_account_list") {
    var userId = binary("user_id", 16)
    var teamAccountId = binary("team_account_id", 16)
    var state = customEnumeration("state", "ENUM('ENABLED', 'DISABLED')", { UserTeamAccountList.Companion.State.valueOf(it as String) }, { it.name })

    override fun fromResultRow(resultRow: ResultRow) =
        UserTeamAccountList(
            resultRow[id],
            resultRow[userId],
            resultRow[teamAccountId],
            resultRow[state],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )

    override fun insert(obj: UserTeamAccountList) =
        insert {
            it[id] = obj.id
            it[userId] = obj.userId
            it[teamAccountId] = obj.teamAccountId
            it[state] = obj.state
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: UserTeamAccountList) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[userId] = obj.userId
            it[teamAccountId] = obj.teamAccountId
            it[state] = obj.state
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}